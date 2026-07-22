<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'guest_id',
        'room_id',
        'membership_type',
        'start_date',
        'end_date',
        'status',
        'amount_paid',
        'access_code',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('membership_type', $type);
    }

    public function isCurrentlyValid(): bool
    {
        return $this->status === 'active'
            && $this->start_date->isPast()
            && $this->end_date->isFuture();
    }
}
