<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'assigned_to',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'priority',
        'check_in_date',
        'check_out_date',
        'guests',
        'room_preference',
        'budget',
        'notes',
        'converted_at',
        'lost_at',
        'lost_reason',
        'last_contacted_at',
        'next_followup_at',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'guests' => 'integer',
            'budget' => 'decimal:2',
            'converted_at' => 'datetime',
            'lost_at' => 'datetime',
            'last_contacted_at' => 'datetime',
            'next_followup_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(CustomerFollowup::class);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['converted', 'lost']);
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    public function scopePendingFollowup($query)
    {
        return $query->where('next_followup_at', '<=', now())
            ->whereNotIn('status', ['converted', 'lost']);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
