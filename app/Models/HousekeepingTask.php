<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousekeepingTask extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_tasks';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'assigned_to',
        'assigned_by',
        'type',
        'status',
        'priority',
        'notes',
        'scheduled_date',
        'scheduled_time',
        'assigned_at',
        'completed_at',
        'inspected_at',
        'inspected_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'assigned_at' => 'datetime',
            'completed_at' => 'datetime',
            'inspected_at' => 'datetime',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}
