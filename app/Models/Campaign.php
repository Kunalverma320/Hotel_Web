<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'description',
        'type',
        'status',
        'budget',
        'spent_amount',
        'start_date',
        'end_date',
        'target_audience',
        'channels',
        'content',
        'landing_url',
        'tracking_code',
        'impressions',
        'clicks',
        'conversions',
        'revenue_generated',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'spent_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'channels' => 'array',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'conversions' => 'integer',
            'revenue_generated' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', today())
            ->where('end_date', '>=', today());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function getClickThroughRateAttribute(): float
    {
        if ($this->impressions === 0) {
            return 0;
        }

        return round(($this->clicks / $this->impressions) * 100, 2);
    }

    public function getConversionRateAttribute(): float
    {
        if ($this->clicks === 0) {
            return 0;
        }

        return round(($this->conversions / $this->clicks) * 100, 2);
    }

    public function getReturnOnInvestmentAttribute(): float
    {
        if ($this->spent_amount === 0) {
            return 0;
        }

        return round((($this->revenue_generated - $this->spent_amount) / $this->spent_amount) * 100, 2);
    }
}
