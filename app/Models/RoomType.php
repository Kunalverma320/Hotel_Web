<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'room_category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'weekend_price',
        'peak_price',
        'max_adults',
        'max_children',
        'max_infants',
        'bed_type',
        'bed_count',
        'room_size',
        'room_size_unit',
        'floor_level',
        'smoking_allowed',
        'pet_allowed',
        'image',
        'status',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'weekend_price' => 'decimal:2',
            'peak_price' => 'decimal:2',
            'max_adults' => 'integer',
            'max_children' => 'integer',
            'max_infants' => 'integer',
            'bed_count' => 'integer',
            'room_size' => 'decimal:2',
            'smoking_allowed' => 'boolean',
            'pet_allowed' => 'boolean',
            'status' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomCategory(): BelongsTo
    {
        return $this->belongsTo(RoomCategory::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 1)->orWhere('status', 'active')->orWhere('status', true);
        });
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('room_category_id', $categoryId);
    }

    public function getAvailableRoomsCount(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }
}
