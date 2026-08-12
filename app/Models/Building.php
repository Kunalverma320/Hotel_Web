<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'code',
        'description',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
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

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function floors(): HasMany
    {
        return $this->hasMany(Floor::class);
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
}
