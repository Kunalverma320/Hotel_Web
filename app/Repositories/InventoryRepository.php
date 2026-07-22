<?php

namespace App\Repositories;

use App\Models\InventoryItem;

class InventoryRepository extends BaseRepository
{
    public function __construct(InventoryItem $model)
    {
        parent::__construct($model);
    }

    public function lowStock(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)
            ->whereColumn('current_stock', '<=', 'reorder_level')
            ->get();
    }

    public function byCategory(int|string $categoryId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('category_id', $categoryId)->get();
    }
}
