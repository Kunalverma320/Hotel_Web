<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'user_id',
        'note',
        'category',
        'is_important',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByGuest($query, int $guestId)
    {
        return $query->where('guest_id', $guestId);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }
}
