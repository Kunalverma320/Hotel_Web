<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait HasAuditLog
{
    public function logActivity(Model $model, string $action, ?array $oldData = null, ?array $newData = null): ActivityLog
    {
        return ActivityLog::create([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'action' => $action,
            'old_data' => $oldData,
            'new_data' => $newData,
            'user_id' => auth()->id(),
            'hotel_id' => $model->hotel_id ?? auth()->user()->hotel_id ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getActivityLogs(Model $model): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::where('auditable_type', get_class($model))
            ->where('auditable_id', $model->getKey())
            ->orderByDesc('created_at')
            ->get();
    }
}
