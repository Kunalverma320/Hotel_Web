<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'hotel_id',
        'payment_number',
        'payment_method',
        'payment_status',
        'amount',
        'currency_code',
        'exchange_rate',
        'amount_in_base_currency',
        'reference_number',
        'transaction_id',
        'card_last_four',
        'card_type',
        'bank_name',
        'cheque_number',
        'processed_by',
        'refund_of_id',
        'notes',
        'processed_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'amount_in_base_currency' => 'decimal:2',
            'processed_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function refundOf(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'refund_of_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Payment::class, 'refund_of_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }
}
