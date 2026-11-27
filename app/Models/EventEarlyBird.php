<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EventEarlyBird extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'discount_type',
        'discount_value',
        'ticket_scope',
        'allowed_ticket_category_ids',
        'ends_at',
        'quantity_limit',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'allowed_ticket_category_ids' => 'array',
        'ends_at' => 'datetime',
        'quantity_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'early_bird_id');
    }

    /**
     * Does this early bird cover the given ticket type?
     */
    public function appliesToCategory($categoryId): bool
    {
        if ($this->ticket_scope !== 'selected_categories') {
            return true;
        }

        return in_array(
            (int) $categoryId,
            array_map('intval', $this->allowed_ticket_category_ids ?? []),
            true
        );
    }

    /**
     * Has the end date and time passed?
     */
    public function hasEnded(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * Early bird tickets already taken: paid orders, plus orders that are
     * still pending and under 5 minutes old (so abandoned orders don't
     * use up the limit).
     */
    public function soldCount(): int
    {
        return Attendee::where('early_bird_id', $this->id)
            ->whereHas('order', function ($query) {
                $query->where(function ($inner) {
                    $inner->whereIn(DB::raw('LOWER(payment_status)'), ['paid', 'approved', 'completed'])
                        ->orWhere(function ($pending) {
                            $pending->where('payment_status', 'pending')
                                ->where('created_at', '>=', now()->subMinutes(5));
                        });
                });
            })
            ->count();
    }

    /**
     * Tickets left at the early bird price, or null when there is no limit.
     */
    public function remaining(): ?int
    {
        if ($this->quantity_limit === null) {
            return null;
        }

        return max(0, $this->quantity_limit - $this->soldCount());
    }

    /**
     * Can a customer still get this early bird right now?
     */
    public function isAvailable(): bool
    {
        if (! $this->is_active || $this->hasEnded()) {
            return false;
        }

        $remaining = $this->remaining();

        return $remaining === null || $remaining > 0;
    }

    /**
     * How much comes off a ticket that costs $price.
     */
    public function discountFor(float $price): float
    {
        $discount = $this->discount_type === 'percentage'
            ? $price * ((float) $this->discount_value / 100)
            : (float) $this->discount_value;

        return round(min($price, max(0.0, $discount)), 2);
    }

    /**
     * The early bird price for a ticket that normally costs $price.
     */
    public function priceFor(float $price): float
    {
        return max(0.0, $price - $this->discountFor($price));
    }
}