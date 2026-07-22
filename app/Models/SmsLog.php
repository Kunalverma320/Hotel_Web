<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_template_id',
        'to_phone',
        'from_phone',
        'message',
        'status',
        'sent_at',
        'delivered_at',
        'error_message',
        'provider',
        'provider_id',
        'cost',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cost' => 'decimal:4',
            'metadata' => 'array',
        ];
    }

    public function smsTemplate(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('sent_at', [$from, $to]);
    }
}
