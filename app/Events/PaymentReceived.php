<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Payment $payment;
    public Booking $booking;

    public function __construct(Payment $payment, Booking $booking)
    {
        $this->payment = $payment;
        $this->booking = $booking;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hotel.' . $this->booking->hotel_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.received';
    }
}
