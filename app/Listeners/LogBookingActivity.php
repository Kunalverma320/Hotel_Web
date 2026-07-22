<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Events\BookingConfirmed;
use App\Events\GuestCheckedIn;
use App\Events\GuestCheckedOut;
use App\Events\PaymentReceived;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class LogBookingActivity
{
    public function subscribe(Dispatcher $events): array
    {
        return [
            BookingConfirmed::class => 'logConfirmed',
            BookingCancelled::class => 'logCancelled',
            GuestCheckedIn::class => 'logCheckIn',
            GuestCheckedOut::class => 'logCheckOut',
            PaymentReceived::class => 'logPayment',
        ];
    }

    public function logConfirmed(BookingConfirmed $event): void
    {
        $booking = $event->booking;
        activity()
            ->performedOn($booking)
            ->withProperties([
                'booking_number' => $booking->booking_number,
                'status' => 'confirmed',
            ])
            ->event('booking_confirmed')
            ->log('Booking ' . $booking->booking_number . ' was confirmed');
    }

    public function logCancelled(BookingCancelled $event): void
    {
        $booking = $event->booking;
        activity()
            ->performedOn($booking)
            ->withProperties([
                'booking_number' => $booking->booking_number,
                'reason' => $event->reason,
                'status' => 'cancelled',
            ])
            ->event('booking_cancelled')
            ->log('Booking ' . $booking->booking_number . ' was cancelled: ' . $event->reason);
    }

    public function logCheckIn(GuestCheckedIn $event): void
    {
        $checkIn = $event->checkIn;
        $booking = $event->booking;
        activity()
            ->performedOn($checkIn)
            ->withProperties([
                'booking_number' => $booking->booking_number,
                'room' => $event->roomNumber,
                'guest' => $booking->guest?->full_name,
            ])
            ->event('guest_checked_in')
            ->log('Guest checked into room ' . $event->roomNumber);
    }

    public function logCheckOut(GuestCheckedOut $event): void
    {
        $checkOut = $event->checkOut;
        $booking = $event->booking;
        activity()
            ->performedOn($checkOut)
            ->withProperties([
                'booking_number' => $booking->booking_number,
                'final_amount' => $checkOut->final_charges,
                'balance' => $checkOut->balance_due,
            ])
            ->event('guest_checked_out')
            ->log('Guest checked out. Final charges: ' . $checkOut->final_charges);
    }

    public function logPayment(PaymentReceived $event): void
    {
        $payment = $event->payment;
        $booking = $event->booking;
        activity()
            ->performedOn($payment)
            ->withProperties([
                'booking_number' => $booking->booking_number,
                'amount' => $payment->amount,
                'method' => $payment->payment_method,
            ])
            ->event('payment_received')
            ->log('Payment of ' . $payment->amount . ' received via ' . $payment->payment_method);
    }
}
