<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'secondary_phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'tax_id',
        'payment_terms',
        'lead_time_days',
        'rating',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'lead_time_days' => 'integer',
            'rating' => 'decimal:1',
            'is_active' => 'boolean',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }
}
