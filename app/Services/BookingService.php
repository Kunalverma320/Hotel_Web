<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Traits\HasAuditLog;
use Illuminate\Support\Facades\DB;

class BookingService
{
    use HasAuditLog;

    public function __construct(
        protected BookingRepository $bookingRepo,
        protected RoomRepository $roomRepo,
    ) {}

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $data['booking_number'] = $this->bookingRepo->generateBookingNumber();
            $data['status'] = 'pending';

            $booking = $this->bookingRepo->create($data);

            $this->logActivity($booking, 'created', null, $booking->toArray());

            return $booking;
        });
    }

    public function confirmBooking(int|string $id): Booking
    {
        return DB::transaction(function () use ($id) {
            $booking = $this->bookingRepo->find($id);
            $oldData = $booking->toArray();

            $booking->update(['status' => 'confirmed']);

            $this->logActivity($booking, 'confirmed', $oldData, $booking->fresh()->toArray());

            return $booking->fresh();
        });
    }

    public function cancelBooking(int|string $id, string $reason): Booking
    {
        return DB::transaction(function () use ($id, $reason) {
            $booking = $this->bookingRepo->find($id);
            $oldData = $booking->toArray();

            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            $this->logActivity($booking, 'cancelled', $oldData, $booking->fresh()->toArray());

            return $booking->fresh();
        });
    }

    public function checkIn(int|string $bookingId, array $data): CheckIn
    {
        return DB::transaction(function () use ($bookingId, $data) {
            $booking = $this->bookingRepo->find($bookingId);

            $checkIn = CheckIn::create(array_merge($data, [
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'checked_in_at' => now(),
            ]));

            $booking->update(['status' => 'checked_in']);

            $this->roomRepo->updateStatus($data['room_id'], 'occupied');

            $this->logActivity($checkIn, 'check_in', null, $checkIn->toArray());

            return $checkIn;
        });
    }

    public function checkOut(int|string $checkInId, array $data): CheckOut
    {
        return DB::transaction(function () use ($checkInId, $data) {
            $checkIn = CheckIn::findOrFail($checkInId);

            $checkOut = CheckOut::create(array_merge($data, [
                'check_in_id' => $checkIn->id,
                'booking_id' => $checkIn->booking_id,
                'hotel_id' => $checkIn->hotel_id,
                'checked_out_at' => now(),
            ]));

            $checkIn->booking->update(['status' => 'checked_out']);

            $this->roomRepo->updateStatus($checkIn->room_id, 'available');
            $this->roomRepo->updateHousekeepingStatus($checkIn->room_id, 'dirty');

            $this->logActivity($checkOut, 'check_out', null, $checkOut->toArray());

            return $checkOut;
        });
    }

    public function allocateRoom(int|string $bookingId, int|string $roomId): Booking
    {
        return DB::transaction(function () use ($bookingId, $roomId) {
            $booking = $this->bookingRepo->find($bookingId);
            $oldData = $booking->toArray();

            $booking->update(['room_id' => $roomId]);

            $this->logActivity($booking, 'room_allocated', $oldData, $booking->fresh()->toArray());

            return $booking->fresh();
        });
    }

    public function generateInvoice(int|string $bookingId): Invoice
    {
        $booking = $this->bookingRepo->find($bookingId)
            ->load(['room.roomType', 'guest', 'checkIns', 'payments']);

        $roomCharges = $booking->checkIns->sum(function ($checkIn) {
            $nights = $checkIn->checked_out_at
                ? $checkIn->checked_out_at->diffInDays($checkIn->checked_in_at)
                : now()->diffInDays($checkIn->checked_in_at);
            return $nights * $booking->room->roomType->rate;
        });

        $totalTax = $roomCharges * ($booking->tax_rate ?? 0) / 100;
        $totalPaid = $booking->payments->sum('amount');

        return Invoice::create([
            'booking_id' => $booking->id,
            'hotel_id' => $booking->hotel_id,
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
            'room_charges' => $roomCharges,
            'tax_amount' => $totalTax,
            'total_amount' => $roomCharges + $totalTax,
            'amount_paid' => $totalPaid,
            'balance_due' => ($roomCharges + $totalTax) - $totalPaid,
            'status' => 'pending',
        ]);
    }

    public function processPayment(int|string $bookingId, array $data): Payment
    {
        return DB::transaction(function () use ($bookingId, $data) {
            $booking = $this->bookingRepo->find($bookingId);

            $payment = Payment::create(array_merge($data, [
                'booking_id' => $booking->id,
                'hotel_id' => $booking->hotel_id,
                'payment_date' => now(),
            ]));

            $this->logActivity($payment, 'payment_processed', null, $payment->toArray());

            return $payment;
        });
    }

    public function getAvailability(int|string $hotelId, string $from, string $to): \Illuminate\Database\Eloquent\Collection
    {
        return $this->roomRepo->available($hotelId, $from, $to);
    }
}
