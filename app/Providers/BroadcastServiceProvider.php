<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['prefix' => 'api', 'middleware' => ['auth:sanctum']]);

        Broadcast::channel('hotel.{hotelId}', function ($user, $hotelId) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
            return (int) $user->hotel_id === (int) $hotelId;
        });

        Broadcast::channel('guest.{guestId}', function ($user, $guestId) {
            return (int) $user->guest_id === (int) $guestId;
        });

        Broadcast::channel('booking.{bookingId}', function ($user, $bookingId) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
            return $user->bookings()->where('id', $bookingId)->exists();
        });

        Broadcast::channel('user.{userId}', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });

        Broadcast::channel('company.{companyId}', function ($user, $companyId) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
            return (int) $user->company_id === (int) $companyId;
        });

        require base_path('routes/channels.php');
    }
}
