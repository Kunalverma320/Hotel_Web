<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'income_number',
        'transaction_id',
        'journal_entry_id',
        'income_category',
        'description',
        'amount',
        'tax_amount',
        'net_amount',
        'reference_type',
        'reference_id',
        'income_date',
        'status',
        'received_from',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'income_date' => 'date',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function reference(): \Illuminate\Database\Eloquent\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('income_category', $category);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('income_date', [$from, $to]);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
