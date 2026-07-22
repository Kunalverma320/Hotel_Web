<?php

namespace App\Listeners;

use App\Events\GuestCheckedIn;
use App\Notifications\CheckInNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendCheckInNotification implements ShouldQueue
{
    public $queue = 'high';

    public function handle(GuestCheckedIn $event): void
    {
        $booking = $event->booking;
        $checkIn = $event->checkIn;
        $guest = $booking->guest;

        if ($guest && $guest->email) {
            try {
                Notification::route('mail', $guest->email)
                    ->notify(new CheckInNotification($checkIn, $booking));
            } catch (\Exception $e) {
                Log::error('Failed to send check-in notification email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($guest && $guest->phone) {
            try {
                Notification::route('sms', $guest->phone)
                    ->notify(new CheckInNotification($checkIn, $booking));
            } catch (\Exception $e) {
                Log::error('Failed to send check-in notification SMS', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
