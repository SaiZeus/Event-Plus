<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'reference',
        'order_number',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
    ];

    /**
     * Boot the model to generate references and attendee ticket details
     * when an order enters a paid-equivalent status.
     */
    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->reference)) {
                $order->reference = 'SWZ-' . strtoupper(Str::random(8));
            }
        });

        static::updated(function ($order) {
            if (! $order->wasChanged('payment_status')) {
                return;
            }

            $paymentStatus = strtolower((string) $order->payment_status);

            if (! in_array($paymentStatus, ['paid', 'approved', 'completed'], true)) {
                return;
            }

            $order->load('attendees.ticketCategory.event');

            foreach ($order->attendees as $attendee) {
                $category = $attendee->ticketCategory;
                $event = $category?->event;

                if (! $category || ! $event || ! empty($attendee->ticket_code)) {
                    continue;
                }

                /*
                 * This observer is the single intended place to increment
                 * tickets_sold. Remove the other increments from the MMQR
                 * callback and checkout controller in the next steps.
                 */
                $category->increment('tickets_sold');

                // 1. Generate the bib number if enabled.
                $generatedBib = $attendee->bib_name;

                if (empty($generatedBib) && $event->enable_bib_number) {
                    $nextNum = 1;

                    if ($event->share_bib_prefix) {
                        $prefix = ! empty($event->event_bib_prefix)
                            ? $event->event_bib_prefix
                            : 'BIB';

                        $startNum = $event->event_bib_start_number ?? 1;

                        $dbBibs = Attendee::whereHas('ticketCategory', function ($query) use ($event) {
                            $query->where('event_id', $event->id);
                        })
                            ->whereNotNull('bib_name')
                            ->pluck('bib_name')
                            ->map(function ($bib) use ($prefix) {
                                $clean = str_replace(
                                    strtoupper($prefix) . '-',
                                    '',
                                    strtoupper($bib)
                                );

                                return is_numeric($clean) ? (int) $clean : null;
                            })
                            ->filter()
                            ->toArray();

                        sort($dbBibs);

                        $nextNum = $startNum;

                        foreach ($dbBibs as $num) {
                            if ($num == $nextNum) {
                                $nextNum++;
                            } elseif ($num > $nextNum) {
                                break;
                            }
                        }
                    } else {
                        $prefix = ! empty($category->bib_prefix)
                            ? $category->bib_prefix
                            : 'BIB';

                        $startNum = $category->bib_start_number ?? 1;

                        $dbBibs = Attendee::where('ticket_category_id', $category->id)
                            ->whereNotNull('bib_name')
                            ->pluck('bib_name')
                            ->map(function ($bib) use ($prefix) {
                                $clean = str_replace(
                                    strtoupper($prefix) . '-',
                                    '',
                                    strtoupper($bib)
                                );

                                return is_numeric($clean) ? (int) $clean : null;
                            })
                            ->filter()
                            ->toArray();

                        sort($dbBibs);

                        $nextNum = $startNum;

                        foreach ($dbBibs as $num) {
                            if ($num == $nextNum) {
                                $nextNum++;
                            } elseif ($num > $nextNum) {
                                break;
                            }
                        }
                    }

                    $generatedBib = strtoupper($prefix)
                        . '-'
                        . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                }

                // 2. Generate the ticket code using the selected BIB prefix mode.
                // Keep the existing ticket-code format (PREFIX0001, without a hyphen).
                $ticketCode = $attendee->ticket_code;

                if (empty($ticketCode)) {
                    if ($event->share_bib_prefix) {
                        $ticketPrefix = ! empty($event->event_bib_prefix)
                            ? strtoupper($event->event_bib_prefix)
                            : 'BGR26';

                        $startRegNum = (int) ($event->event_bib_start_number ?? 1);

                        // Shared mode: find used numbers across every category in this event.
                        $dbRegs = Attendee::whereHas('ticketCategory', function ($query) use ($event) {
                            $query->where('event_id', $event->id);
                        })
                            ->whereNotNull('ticket_code')
                            ->pluck('ticket_code')
                            ->map(function ($code) use ($ticketPrefix) {
                                $code = strtoupper((string) $code);

                                if (! str_starts_with($code, $ticketPrefix)) {
                                    return null;
                                }

                                $clean = substr($code, strlen($ticketPrefix));

                                return is_numeric($clean) ? (int) $clean : null;
                            })
                            ->filter(fn ($num) => $num !== null)
                            ->toArray();
                    } else {
                        $ticketPrefix = ! empty($category->bib_prefix)
                            ? strtoupper($category->bib_prefix)
                            : 'BGR26';

                        $startRegNum = (int) ($category->bib_start_number ?? 1);

                        // Separate mode: find used numbers only within this ticket category.
                        $dbRegs = Attendee::where('ticket_category_id', $category->id)
                            ->whereNotNull('ticket_code')
                            ->pluck('ticket_code')
                            ->map(function ($code) use ($ticketPrefix) {
                                $code = strtoupper((string) $code);

                                if (! str_starts_with($code, $ticketPrefix)) {
                                    return null;
                                }

                                $clean = substr($code, strlen($ticketPrefix));

                                return is_numeric($clean) ? (int) $clean : null;
                            })
                            ->filter(fn ($num) => $num !== null)
                            ->toArray();
                    }

                    sort($dbRegs);

                    $nextRegNum = $startRegNum;

                    foreach ($dbRegs as $num) {
                        if ($num == $nextRegNum) {
                            $nextRegNum++;
                        } elseif ($num > $nextRegNum) {
                            break;
                        }
                    }

                    $ticketCode = $ticketPrefix
                        . str_pad($nextRegNum, 4, '0', STR_PAD_LEFT);
                }

                // Save the generated codes.
                $attendee->update([
                    'bib_name' => $generatedBib,
                    'ticket_code' => $ticketCode,
                ]);

                // Increment promo usage if applicable.
                if (! empty($attendee->promo_code_id)) {
                    $promo = PromoCode::find($attendee->promo_code_id);

                    if ($promo) {
                        $promo->increment('uses_count');
                    }
                }
            }
        });
    }

    /**
     * Use the reference instead of the ID in URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    /**
     * Relationship to Event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship to Attendees.
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
