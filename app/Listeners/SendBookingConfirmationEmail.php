<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Mail\BookingConfirmation;
use App\Models\Setting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationEmail implements ShouldQueue
{
    public $queue = 'high';

    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking;
        $guest = $booking->guest;

        if (!$guest || !$guest->email) {
            Log::warning('Booking confirmation email skipped: no guest email', ['booking_id' => $booking->id]);
            return;
        }

        try {
            Mail::to($guest->email)->send(new BookingConfirmation($booking));
            activity()
                ->performedOn($booking)
                ->causedBy($booking->guest)
                ->withProperties(['email' => $guest->email])
                ->event('email_sent')
                ->log('Booking confirmation email sent to ' . $guest->email);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
