<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Invoice;
use App\Services\FinanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceJob implements ShouldQueue
{
    use Queueable;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function handle(FinanceService $financeService): void
    {
        try {
            $booking = $this->booking->load(['hotel', 'guest', 'roomType', 'bookingRooms.room', 'payments', 'charges']);

            $existingInvoice = Invoice::where('booking_id', $booking->id)->latest()->first();

            if ($existingInvoice && $existingInvoice->pdf_path && Storage::exists($existingInvoice->pdf_path)) {
                Log::info('Invoice already exists for booking', ['booking_id' => $booking->id]);
                return;
            }

            $roomCharges = $booking->room_rate * $booking->nights;
            $additionalCharges = $booking->charges->sum('amount') ?? 0;
            $totalTax = $booking->tax_amount ?? ($roomCharges * 0.18);
            $totalAmount = $roomCharges + $additionalCharges + $totalTax;
            $amountPaid = $booking->payments->sum('amount') ?? 0;
            $balanceDue = $totalAmount - $amountPaid;

            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'guest_id' => $booking->guest_id,
                'invoice_number' => $invoiceNumber,
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'room_charges' => $roomCharges,
                'additional_charges' => $additionalCharges,
                'tax_amount' => $totalTax,
                'total_amount' => $totalAmount,
                'amount_paid' => $amountPaid,
                'balance_due' => $balanceDue,
                'status' => $balanceDue <= 0 ? 'paid' : 'pending',
                'currency_code' => $booking->currency_code ?? 'USD',
            ]);

            $pdf = Pdf::loadView('invoices.booking', [
                'invoice' => $invoice,
                'booking' => $booking,
            ]);

            $pdfPath = 'invoices/invoice-' . $invoice->id . '.pdf';
            Storage::put($pdfPath, $pdf->output());

            $invoice->update(['pdf_path' => $pdfPath]);

            activity()
                ->performedOn($invoice)
                ->withProperties(['booking_id' => $booking->id, 'invoice_number' => $invoiceNumber])
                ->event('invoice_generated')
                ->log('Invoice generated for booking ' . $booking->booking_number);
        } catch (\Exception $e) {
            Log::error('GenerateInvoiceJob failed', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
