<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\CheckOut;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestCheckedOut implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CheckOut $checkOut;
    public Booking $booking;

    public function __construct(CheckOut $checkOut, Booking $booking)
    {
        $this->checkOut = $checkOut;
        $this->booking = $booking;
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
        return 'guest.checked_out';
    }
}
