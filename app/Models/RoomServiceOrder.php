<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'order_number',
        'room_id',
        'booking_id',
        'kitchen_order_id',
        'ordered_by',
        'delivery_by',
        'status',
        'total_amount',
        'service_charge',
        'delivery_time',
        'special_instructions',
        'delivered_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'delivery_time' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function kitchenOrder(): BelongsTo
    {
        return $this->belongsTo(KitchenOrder::class);
    }

    public function deliveryBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_by');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}
