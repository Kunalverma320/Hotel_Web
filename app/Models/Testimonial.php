<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'hotel_id',
        'rating',
        'title',
        'content',
        'source',
        'is_approved',
        'is_featured',
        'response',
        'response_by',
        'response_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'is_approved' => 'boolean',
            'is_featured' => 'boolean',
            'response_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function responseBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'response_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByRating($query, float $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
