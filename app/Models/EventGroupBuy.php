<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroupBuy extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'quantity',
        'discount_amount',
        'ticket_scope',
        'allowed_ticket_category_ids',
        'is_active',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'discount_amount' => 'decimal:2',
        'allowed_ticket_category_ids' => 'array',
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
{
    return $this->hasMany(Order::class, 'group_buy_id');
}

}