<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwimmingPoolSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_open',
        'max_capacity',
        'notes',
        'season_start',
        'season_end',
    ];

    protected function casts(): array
    {
        return [
            'opening_time' => 'datetime:H:i',
            'closing_time' => 'datetime:H:i',
            'is_open' => 'boolean',
            'max_capacity' => 'integer',
            'season_start' => 'date',
            'season_end' => 'date',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeByDay($query, string $day)
    {
        return $query->where('day_of_week', $day);
    }
}
