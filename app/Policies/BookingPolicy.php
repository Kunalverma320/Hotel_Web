<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('bookings.view');
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.view')
            && ($user->hotel_id === $booking->hotel_id || $user->hasRole('super-admin'));
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('bookings.create');
    }

    public function update(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.update')
            && ($user->hotel_id === $booking->hotel_id || $user->hasRole('super-admin'));
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.delete')
            && ($user->hotel_id === $booking->hotel_id);
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.cancel')
            && ($user->hotel_id === $booking->hotel_id)
            && in_array($booking->status, ['pending', 'confirmed']);
    }

    public function checkIn(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.check_in')
            && ($user->hotel_id === $booking->hotel_id)
            && $booking->status === 'confirmed';
    }

    public function checkOut(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.check_out')
            && ($user->hotel_id === $booking->hotel_id)
            && $booking->status === 'checked_in';
    }

    public function allocateRoom(User $user, Booking $booking): bool
    {
        return $user->hasPermissionTo('bookings.allocate_room')
            && ($user->hotel_id === $booking->hotel_id)
            && in_array($booking->status, ['pending', 'confirmed']);
    }
}
