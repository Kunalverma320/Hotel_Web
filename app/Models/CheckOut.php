<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_id',
        'booking_id',
        'room_id',
        'checked_out_by',
        'check_out_time',
        'final_charges',
        'amount_paid',
        'balance_due',
        'refund_amount',
        'late_checkout_fee',
        'minibar_charges',
        'damage_charges',
        'condition_notes',
        'key_cards_returned',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'check_out_time' => 'datetime',
            'final_charges' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'refund_amount' => 'decimal:2',
            'late_checkout_fee' => 'decimal:2',
            'minibar_charges' => 'decimal:2',
            'damage_charges' => 'decimal:2',
            'key_cards_returned' => 'integer',
        ];
    }

    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
