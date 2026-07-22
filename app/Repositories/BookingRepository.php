<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;

class BookingRepository extends BaseRepository
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    public function byHotel(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)->get();
    }

    public function byGuest(int|string $guestId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('guest_id', $guestId)->get();
    }

    public function byStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('status', $status)->get();
    }

    public function byDateRange(string $from, string $to): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->whereBetween('date_from', [$from, $to])->get();
    }

    public function todayCheckins(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)
            ->whereDate('date_from', now()->toDateString())
            ->where('status', 'confirmed')
            ->get();
    }

    public function todayCheckouts(int|string $hotelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('hotel_id', $hotelId)
            ->whereDate('date_to', now()->toDateString())
            ->where('status', 'checked_in')
            ->get();
    }

    public function generateBookingNumber(): string
    {
        $prefix = 'BK';
        $date = now()->format('Ymd');
        $lastBooking = $this->model->where('booking_number', 'like', "{$prefix}{$date}%")
            ->orderByDesc('booking_number')
            ->first();

        if ($lastBooking) {
            $sequence = (int) substr($lastBooking->booking_number, -4) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s%s%04d', $prefix, $date, $sequence);
    }
}
