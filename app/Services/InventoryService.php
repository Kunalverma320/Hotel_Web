<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Repositories\InventoryRepository;
use App\Traits\HasAuditLog;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    use HasAuditLog;

    public function __construct(
        protected InventoryRepository $inventoryRepo,
    ) {}

    public function createItem(array $data): InventoryItem
    {
        $item = $this->inventoryRepo->create($data);

        $this->logActivity($item, 'created', null, $item->toArray());

        return $item;
    }

    public function stockIn(int|string $itemId, float $qty, string $reference): StockMovement
    {
        return DB::transaction(function () use ($itemId, $qty, $reference) {
            $item = $this->inventoryRepo->find($itemId);

            $movement = StockMovement::create([
                'hotel_id' => $item->hotel_id,
                'item_id' => $itemId,
                'type' => 'in',
                'quantity' => $qty,
                'reference' => $reference,
                'balance_after' => $item->current_stock + $qty,
            ]);

            $item->increment('current_stock', $qty);

            $this->logActivity($movement, 'stock_in', null, $movement->toArray());

            return $movement;
        });
    }

    public function stockOut(int|string $itemId, float $qty, string $reference): StockMovement
    {
        return DB::transaction(function () use ($itemId, $qty, $reference) {
            $item = $this->inventoryRepo->find($itemId);

            if ($item->current_stock < $qty) {
                throw new \InvalidArgumentException("Insufficient stock for item {$item->name}. Available: {$item->current_stock}, Requested: {$qty}");
            }

            $movement = StockMovement::create([
                'hotel_id' => $item->hotel_id,
                'item_id' => $itemId,
                'type' => 'out',
                'quantity' => $qty,
                'reference' => $reference,
                'balance_after' => $item->current_stock - $qty,
            ]);

            $item->decrement('current_stock', $qty);

            $this->logActivity($movement, 'stock_out', null, $movement->toArray());

            return $movement;
        });
    }

    public function transferStock(int|string $fromItemId, int|string $toItemId, float $qty): array
    {
        return DB::transaction(function () use ($fromItemId, $toItemId, $qty) {
            $outMovement = $this->stockOut($fromItemId, $qty, 'transfer_to:' . $toItemId);
            $inMovement = $this->stockIn($toItemId, $qty, 'transfer_from:' . $fromItemId);

            return ['out' => $outMovement, 'in' => $inMovement];
        });
    }

    public function adjustStock(int|string $itemId, float $qty, string $reason): StockMovement
    {
        return DB::transaction(function () use ($itemId, $qty, $reason) {
            $item = $this->inventoryRepo->find($itemId);

            $type = $qty >= 0 ? 'adjustment_in' : 'adjustment_out';
            $absoluteQty = abs($qty);

            $movement = StockMovement::create([
                'hotel_id' => $item->hotel_id,
                'item_id' => $itemId,
                'type' => $type,
                'quantity' => $absoluteQty,
                'reference' => "Adjustment: {$reason}",
                'balance_after' => $item->current_stock + $qty,
            ]);

            $item->increment('current_stock', $qty);

            $this->logActivity($movement, 'stock_adjusted', null, $movement->toArray());

            return $movement;
        });
    }

    public function lowStockAlerts(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->inventoryRepo->lowStock($hotelId);
    }
}
