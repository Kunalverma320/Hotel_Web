<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'preference_type',
        'preference_key',
        'preference_value',
        'notes',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('preference_type', $type);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('preference_key', $key);
    }
}
