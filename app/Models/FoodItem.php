<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'menu_category_id',
        'name',
        'slug',
        'description',
        'price',
        'cost_price',
        'image',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'is_halal',
        'is_kosher',
        'allergens',
        'preparation_time',
        'is_available',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'is_vegetarian' => 'boolean',
            'is_vegan' => 'boolean',
            'is_gluten_free' => 'boolean',
            'is_halal' => 'boolean',
            'is_kosher' => 'boolean',
            'allergens' => 'array',
            'preparation_time' => 'integer',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function kitchenOrderItems(): HasMany
    {
        return $this->hasMany(KitchenOrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('menu_category_id', $categoryId);
    }

    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }
}
