<?php

namespace App\Events;

use App\Models\Hotel;
use App\Models\InventoryItem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public InventoryItem $item;
    public Hotel $hotel;
    public float $currentStock;
    public float $reorderPoint;

    public function __construct(InventoryItem $item, Hotel $hotel)
    {
        $this->item = $item;
        $this->hotel = $hotel;
        $this->currentStock = (float) $item->current_stock;
        $this->reorderPoint = (float) $item->reorder_point;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hotel.' . $this->hotel->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'stock.low';
    }
}
