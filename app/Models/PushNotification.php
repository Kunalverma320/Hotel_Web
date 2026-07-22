<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'url',
        'icon',
        'image',
        'target_type',
        'target_ids',
        'status',
        'sent_at',
        'sent_count',
        'open_count',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'target_ids' => 'array',
            'sent_at' => 'datetime',
            'sent_count' => 'integer',
            'open_count' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByTarget($query, string $type)
    {
        return $query->where('target_type', $type);
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->sent_count === 0) {
            return 0;
        }

        return round(($this->open_count / $this->sent_count) * 100, 2);
    }
}
