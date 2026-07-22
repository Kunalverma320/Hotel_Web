<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'inventory_item_id',
        'movement_type',
        'quantity',
        'reference_type',
        'reference_id',
        'unit_cost',
        'total_cost',
        'performed_by',
        'notes',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'performed_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function reference(): \Illuminate\Database\Eloquent\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByItem($query, int $itemId)
    {
        return $query->where('inventory_item_id', $itemId);
    }

    public function scopeInbound($query)
    {
        return $query->where('movement_type', 'inbound');
    }

    public function scopeOutbound($query)
    {
        return $query->where('movement_type', 'outbound');
    }
}
