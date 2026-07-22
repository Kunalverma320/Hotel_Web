<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'number',
        'name',
        'capacity',
        'location',
        'status',
        'table_type',
        'min_charge',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'min_charge' => 'decimal:2',
            'is_active' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function kitchenOrders(): HasMany
    {
        return $this->hasMany(KitchenOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('table_type', $type);
    }

    public function scopeByCapacity($query, int $minCapacity)
    {
        return $query->where('capacity', '>=', $minCapacity);
    }
}
