<?php

namespace App\Services;

use App\Models\HousekeepingTask;
use App\Repositories\RoomRepository;
use App\Traits\HasAuditLog;
use Illuminate\Support\Facades\DB;

class HousekeepingService
{
    use HasAuditLog;

    public function __construct(
        protected RoomRepository $roomRepo,
    ) {}

    public function assignRoom(int|string $hotelId, int|string $roomId, int|string $userId, string $type): HousekeepingTask
    {
        return DB::transaction(function () use ($hotelId, $roomId, $userId, $type) {
            $task = HousekeepingTask::create([
                'hotel_id' => $hotelId,
                'room_id' => $roomId,
                'assigned_to' => $userId,
                'type' => $type,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            $this->roomRepo->updateHousekeepingStatus($roomId, 'in_progress');

            $this->logActivity($task, 'assigned', null, $task->toArray());

            return $task;
        });
    }

    public function updateStatus(int|string $id, string $status): HousekeepingTask
    {
        return DB::transaction(function () use ($id, $status) {
            $task = HousekeepingTask::findOrFail($id);
            $oldData = $task->toArray();

            $task->update([
                'status' => $status,
                'completed_at' => $status === 'completed' ? now() : null,
            ]);

            if ($status === 'completed') {
                $this->roomRepo->updateHousekeepingStatus($task->room_id, 'clean');
            }

            $this->logActivity($task, 'status_updated', $oldData, $task->fresh()->toArray());

            return $task->fresh();
        });
    }

    public function getTodaysSchedule(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return HousekeepingTask::where('hotel_id', $hotelId)
            ->whereDate('created_at', now()->toDateString())
            ->with(['room', 'assignee'])
            ->get();
    }

    public function getRoomStatus(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roomRepo->model->where('hotel_id', $hotelId)
            ->select('id', 'number', 'status', 'housekeeping_status')
            ->get();
    }
}
