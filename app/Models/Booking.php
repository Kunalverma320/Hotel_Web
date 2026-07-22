<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'booking_number',
        'room_type_id',
        'guest_id',
        'corporate_id',
        'travel_agent_id',
        'source',
        'status',
        'check_in_date',
        'check_out_date',
        'actual_check_in',
        'actual_check_out',
        'adults',
        'children',
        'infants',
        'nights',
        'room_rate',
        'total_amount',
        'paid_amount',
        'discount_amount',
        'tax_amount',
        'currency_code',
        'exchange_rate',
        'special_requests',
        'internal_notes',
        'cancellation_reason',
        'cancelled_at',
        'confirmed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'actual_check_in' => 'datetime',
            'actual_check_out' => 'datetime',
            'adults' => 'integer',
            'children' => 'integer',
            'infants' => 'integer',
            'nights' => 'integer',
            'room_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'exchange_rate' => 'decimal:6',
            'cancelled_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function corporate(): BelongsTo
    {
        return $this->belongsTo(Corporate::class);
    }

    public function travelAgent(): BelongsTo
    {
        return $this->belongsTo(TravelAgent::class);
    }

    public function bookingRooms(): HasMany
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_rooms', 'booking_id', 'room_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function checkOuts(): HasMany
    {
        return $this->hasMany(CheckOut::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'checked_in']);
    }

    public function scopeByHotel($query, int $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeTodayCheckIns($query)
    {
        return $query->whereDate('check_in_date', today());
    }

    public function scopeTodayCheckOuts($query)
    {
        return $query->whereDate('check_out_date', today());
    }

    public function getBalanceAttribute(): float
    {
        return (float) $this->total_amount - (float) $this->paid_amount;
    }
}
