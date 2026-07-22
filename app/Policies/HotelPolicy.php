<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;

class HotelPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('hotels.view');
    }

    public function view(User $user, Hotel $hotel): bool
    {
        return $user->hasPermissionTo('hotels.view')
            && ($user->hotel_id === $hotel->id || $user->hasRole('super-admin'));
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('hotels.create');
    }

    public function update(User $user, Hotel $hotel): bool
    {
        return $user->hasPermissionTo('hotels.update')
            && ($user->hotel_id === $hotel->id || $user->hasRole('super-admin'));
    }

    public function delete(User $user, Hotel $hotel): bool
    {
        return $user->hasPermissionTo('hotels.delete')
            && $user->hasRole('super-admin');
    }

    public function manageSettings(User $user, Hotel $hotel): bool
    {
        return $user->hasPermissionTo('hotels.manage_settings')
            && ($user->hotel_id === $hotel->id || $user->hasRole('super-admin'));
    }
}
