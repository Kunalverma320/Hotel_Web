<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasHotel
{
    public function scopeHotel(Builder $query, int|string $hotelId): Builder
    {
        return $query->where('hotel_id', $hotelId);
    }

    public static function bootHasHotel(): void
    {
        static::creating(function ($model) {
            if (is_null($model->hotel_id) && auth()->check()) {
                $model->hotel_id = auth()->user()->hotel_id;
            }
        });
    }
}
