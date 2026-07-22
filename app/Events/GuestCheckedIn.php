<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\CheckIn;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestCheckedIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CheckIn $checkIn;
    public Booking $booking;
    public string $roomNumber;

    public function __construct(CheckIn $checkIn, Booking $booking)
    {
        $this->checkIn = $checkIn;
        $this->booking = $booking;
        $this->roomNumber = $checkIn->relationLoaded('room') ? $checkIn->room->number : '';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hotel.' . $this->booking->hotel_id),
            new PrivateChannel('guest.' . $this->booking->guest_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'guest.checked_in';
    }
}
