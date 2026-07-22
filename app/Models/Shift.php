<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'code',
        'start_time',
        'end_time',
        'break_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'break_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function getDurationInHours(): float
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);

        if ($end < $start) {
            $end += 86400;
        }

        return round(($end - $start) / 3600 - ($this->break_minutes / 60), 2);
    }
}
