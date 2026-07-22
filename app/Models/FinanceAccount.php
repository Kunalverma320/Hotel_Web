<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class FinanceAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'parent_id',
        'code',
        'name',
        'description',
        'type',
        'sub_type',
        'normal_balance',
        'opening_balance',
        'current_balance',
        'currency_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(FinanceAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(FinanceAccount::class, 'parent_id');
    }

    public function journalEntryItems(): HasMany
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
