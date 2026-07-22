<?php

namespace App\Providers;

use App\Events\BookingCancelled;
use App\Events\BookingConfirmed;
use App\Events\GuestCheckedIn;
use App\Events\GuestCheckedOut;
use App\Events\LowStockAlert;
use App\Events\PaymentReceived;
use App\Listeners\LogBookingActivity;
use App\Listeners\SendBookingConfirmationEmail;
use App\Listeners\SendCheckInNotification;
use App\Listeners\SendCheckOutInvoice;
use App\Listeners\UpdateLoyaltyPoints;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookingConfirmed::class => [
            SendBookingConfirmationEmail::class,
            LogBookingActivity::class . '@logConfirmed',
        ],
        BookingCancelled::class => [
            LogBookingActivity::class . '@logCancelled',
        ],
        GuestCheckedIn::class => [
            SendCheckInNotification::class,
            LogBookingActivity::class . '@logCheckIn',
        ],
        GuestCheckedOut::class => [
            SendCheckOutInvoice::class,
            UpdateLoyaltyPoints::class,
            LogBookingActivity::class . '@logCheckOut',
        ],
        PaymentReceived::class => [
            LogBookingActivity::class . '@logPayment',
        ],
        LowStockAlert::class => [
            \App\Listeners\SendLowStockNotification::class,
        ],
    ];

    protected $subscribe = [
        LogBookingActivity::class,
    ];

    public function boot(): void
    {
        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
