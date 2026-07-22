<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'room_id',
        'guest_id',
        'hotel_id',
        'checked_in_by',
        'check_in_time',
        'key_cards_issued',
        'deposit_amount',
        'deposit_type',
        'id_verified',
        'id_verified_by',
        'special_requests',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
            'key_cards_issued' => 'integer',
            'deposit_amount' => 'decimal:2',
            'id_verified' => 'boolean',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }
}
