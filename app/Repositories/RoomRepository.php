<?php

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;

class RoomRepository extends BaseRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }

    public function available(int|string $hotelId, string $dateFrom, string $dateTo): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)
            ->where('status', 'available')
            ->where('housekeeping_status', 'clean')
            ->whereDoesntHave('bookings', function (Builder $query) use ($dateFrom, $dateTo) {
                $query->whereBetween('date_from', [$dateFrom, $dateTo])
                    ->orWhereBetween('date_to', [$dateFrom, $dateTo])
                    ->orWhere(function (Builder $q) use ($dateFrom, $dateTo) {
                        $q->where('date_from', '<=', $dateFrom)
                            ->where('date_to', '>=', $dateTo);
                    })
                    ->whereIn('status', ['confirmed', 'checked_in']);
            })
            ->get();
    }

    public function byRoomType(int|string $roomTypeId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('room_type_id', $roomTypeId)->get();
    }

    public function updateStatus(int|string $id, string $status): Model
    {
        $room = $this->find($id);
        $room->update(['status' => $status]);
        return $room->fresh();
    }

    public function updateHousekeepingStatus(int|string $id, string $status): Model
    {
        $room = $this->find($id);
        $room->update(['housekeeping_status' => $status]);
        return $room->fresh();
    }
}
