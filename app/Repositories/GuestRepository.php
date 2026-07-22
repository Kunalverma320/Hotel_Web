<?php

namespace App\Repositories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Builder;

class GuestRepository extends BaseRepository
{
    public function __construct(Guest $model)
    {
        parent::__construct($model);
    }

    public function search(string $term): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where(function (Builder $query) use ($term) {
            $query->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('passport_number', 'like', "%{$term}%");
        })->get();
    }

    public function byNationality(string $nationality): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('nationality', $nationality)->get();
    }

    public function vipGuests(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_vip', true)->get();
    }

    public function blacklist(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_blacklisted', true)->get();
    }

    public function byLoyaltyPoints(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->orderByDesc('loyalty_points')->get();
    }
}
