<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'code',
        'initial_balance',
        'current_balance',
        'purchaser_name',
        'purchaser_email',
        'recipient_name',
        'recipient_email',
        'status',
        'expiry_date',
        'activated_at',
        'last_used_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'initial_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'expiry_date' => 'date',
            'activated_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', today());
        });
    }

    public function isValid(): bool
    {
        return $this->status === 'active'
            && $this->current_balance > 0
            && (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    public function redeem(float $amount): bool
    {
        if (!$this->isValid() || $amount > $this->current_balance) {
            return false;
        }

        $this->decrement('current_balance', $amount);
        $this->update(['last_used_at' => now()]);

        if ($this->current_balance <= 0) {
            $this->update(['status' => 'redeemed']);
        }

        return true;
    }
}
