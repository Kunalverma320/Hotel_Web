<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'building_id',
        'name',
        'floor_number',
        'number',
        'description',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'floor_number' => 'integer',
            'number' => 'integer',
            'status' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status'] ?? false);
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status'] = in_array($value, ['active', '1', 1, true], true) ? 1 : 0;
    }

    public function getNumberAttribute()
    {
        return $this->attributes['floor_number'] ?? $this->attributes['number'] ?? 0;
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
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

    public function scopeByBuilding($query, int $buildingId)
    {
        return $query->where('building_id', $buildingId);
    }
}
