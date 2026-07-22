<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'secondary_phone',
        'date_of_birth',
        'gender',
        'nationality',
        'id_type',
        'id_number',
        'company_name',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'notes',
        'is_blacklisted',
        'blacklist_reason',
        'loyalty_points',
        'loyalty_tier',
        'total_stays',
        'total_spent',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_blacklisted' => 'boolean',
            'loyalty_points' => 'integer',
            'total_stays' => 'integer',
            'total_spent' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(GuestDocument::class);
    }

    public function preferences(): HasMany
    {
        return $this->hasMany(GuestPreference::class);
    }

    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function spaAppointments(): HasMany
    {
        return $this->hasMany(SpaAppointment::class);
    }

    public function gymMemberships(): HasMany
    {
        return $this->hasMany(GymMembership::class);
    }

    public function laundryOrders(): HasMany
    {
        return $this->hasMany(LaundryOrder::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function customerNotes(): HasMany
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function scopeBlacklisted($query)
    {
        return $query->where('is_blacklisted', true);
    }

    public function scopeByLoyaltyTier($query, string $tier)
    {
        return $query->where('loyalty_tier', $tier);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
