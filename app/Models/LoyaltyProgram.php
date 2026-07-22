<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'points_per_dollar',
        'redemption_rate',
        'minimum_points',
        'tier_thresholds',
        'tier_benefits',
        'is_active',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'points_per_dollar' => 'decimal:2',
            'redemption_rate' => 'decimal:4',
            'minimum_points' => 'integer',
            'tier_thresholds' => 'array',
            'tier_benefits' => 'array',
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function calculatePoints(float $amount): int
    {
        return (int) floor($amount * $this->points_per_dollar);
    }

    public function calculateRedemptionValue(int $points): float
    {
        return round($points * $this->redemption_rate, 2);
    }
}
