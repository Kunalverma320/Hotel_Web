<?php

namespace App\Listeners;

use App\Events\GuestCheckedOut;
use App\Jobs\GenerateInvoiceJob;
use App\Models\Invoice;
use App\Notifications\CheckOutInvoiceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendCheckOutInvoice implements ShouldQueue
{
    public $queue = 'high';

    public function handle(GuestCheckedOut $event): void
    {
        $booking = $event->booking;
        $checkOut = $event->checkOut;

        try {
            $invoice = Invoice::where('booking_id', $booking->id)->latest()->first();

            if (!$invoice) {
                GenerateInvoiceJob::dispatch($booking);
                return;
            }

            $guest = $booking->guest;

            if ($guest && $guest->email) {
                $pdfPath = storage_path('app/invoices/invoice-' . $invoice->id . '.pdf');

                if (file_exists($pdfPath)) {
                    Notification::route('mail', $guest->email)
                        ->notify(new CheckOutInvoiceNotification($booking, $invoice, $pdfPath));
                } else {
                    Notification::route('mail', $guest->email)
                        ->notify(new CheckOutInvoiceNotification($booking, $invoice));
                }

                activity()
                    ->performedOn($invoice)
                    ->causedBy($booking->guest)
                    ->withProperties(['email' => $guest->email])
                    ->event('email_sent')
                    ->log('Check-out invoice sent to ' . $guest->email);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send check-out invoice', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
