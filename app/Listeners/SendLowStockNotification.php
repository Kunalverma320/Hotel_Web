<?php

namespace App\Listeners;

use App\Events\LowStockAlert;
use App\Notifications\LowStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendLowStockNotification implements ShouldQueue
{
    public $queue = 'low';

    public function handle(LowStockAlert $event): void
    {
        try {
            $item = $event->item;
            $hotel = $event->hotel;

            $inventoryManagers = \App\Models\User::role(['admin', 'manager', 'accountant'])
                ->where('hotel_id', $hotel->id)
                ->get();

            if ($inventoryManagers->isNotEmpty()) {
                Notification::send($inventoryManagers, new LowStockNotification($item, $hotel));
            }

            activity()
                ->performedOn($item)
                ->withProperties([
                    'item' => $item->name,
                    'current_stock' => $event->currentStock,
                    'reorder_point' => $event->reorderPoint,
                    'hotel' => $hotel->name,
                ])
                ->event('low_stock_alert')
                ->log('Low stock alert: ' . $item->name . ' (' . $event->currentStock . ' remaining)');
        } catch (\Exception $e) {
            Log::error('SendLowStockNotification failed', [
                'item_id' => $event->item->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
