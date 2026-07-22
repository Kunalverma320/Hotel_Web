<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use App\Repositories\BookingRepository;
use App\Repositories\RoomRepository;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookingService $bookingService;
    private Hotel $hotel;
    private RoomType $roomType;
    private Guest $guest;
    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hotel = Hotel::factory()->create();
        $this->roomType = RoomType::factory()->create([
            'hotel_id' => $this->hotel->id,
            'base_price' => 200.00,
        ]);
        $this->guest = Guest::factory()->create();
        $this->room = Room::factory()->create([
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'status' => 'available',
        ]);

        $bookingRepo = $this->app->make(BookingRepository::class);
        $roomRepo = $this->app->make(RoomRepository::class);
        $this->bookingService = new BookingService($bookingRepo, $roomRepo);
    }

    public function test_create_booking(): void
    {
        $bookingData = [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => now()->addDays(5)->format('Y-m-d'),
            'check_out_date' => now()->addDays(7)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'nights' => 2,
            'room_rate' => 200.00,
            'total_amount' => 472.00,
            'tax_amount' => 72.00,
        ];

        $booking = $this->bookingService->createBooking($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals('pending', $booking->status);
        $this->assertNotNull($booking->booking_number);
        $this->assertEquals($this->hotel->id, $booking->hotel_id);
    }

    public function test_confirm_booking(): void
    {
        $booking = Booking::factory()->pending()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
        ]);

        $confirmed = $this->bookingService->confirmBooking($booking->id);

        $this->assertEquals('confirmed', $confirmed->status);
    }

    public function test_cancel_booking(): void
    {
        $booking = Booking::factory()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
        ]);

        $cancelled = $this->bookingService->cancelBooking($booking->id, 'Guest changed plans');

        $this->assertEquals('cancelled', $cancelled->status);
        $this->assertEquals('Guest changed plans', $cancelled->cancellation_reason);
        $this->assertNotNull($cancelled->cancelled_at);
    }

    public function test_check_in(): void
    {
        $booking = Booking::factory()->confirmed()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
            'room_type_id' => $this->roomType->id,
        ]);

        $checkInData = [
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'key_cards_issued' => 2,
        ];

        $checkIn = $this->bookingService->checkIn($booking->id, $checkInData);

        $this->assertInstanceOf(CheckIn::class, $checkIn);
        $this->assertEquals('checked_in', $booking->fresh()->status);
        $this->assertEquals('occupied', $this->room->fresh()->status);
    }

    public function test_check_out(): void
    {
        $booking = Booking::factory()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
            'status' => 'checked_in',
            'room_type_id' => $this->roomType->id,
        ]);

        $checkIn = CheckIn::factory()->create([
            'booking_id' => $booking->id,
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'hotel_id' => $this->hotel->id,
        ]);

        $this->room->update(['status' => 'occupied']);

        $checkOutData = [
            'room_id' => $this->room->id,
            'final_charges' => 472.00,
            'amount_paid' => 472.00,
            'balance_due' => 0,
            'key_cards_returned' => 2,
        ];

        $checkOut = $this->bookingService->checkOut($checkIn->id, $checkOutData);

        $this->assertInstanceOf(CheckOut::class, $checkOut);
        $this->assertEquals('checked_out', $booking->fresh()->status);
        $this->assertEquals('available', $this->room->fresh()->status);
    }

    public function test_allocate_room(): void
    {
        $booking = Booking::factory()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
        ]);

        $updated = $this->bookingService->allocateRoom($booking->id, $this->room->id);

        $this->assertEquals($this->room->id, $updated->room_id);
    }

    public function test_process_payment(): void
    {
        $booking = Booking::factory()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
            'total_amount' => 500.00,
        ]);

        $paymentData = [
            'payment_number' => 'PAY-TEST-001',
            'payment_method' => 'credit_card',
            'payment_status' => 'completed',
            'amount' => 500.00,
            'processed_by' => 1,
        ];

        $payment = $this->bookingService->processPayment($booking->id, $paymentData);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals(500.00, $payment->amount);
        $this->assertEquals('credit_card', $payment->payment_method);
    }

    public function test_get_availability(): void
    {
        Booking::factory()->confirmed()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(10)->format('Y-m-d'),
            'check_out_date' => now()->addDays(12)->format('Y-m-d'),
        ]);

        $availableRooms = $this->bookingService->getAvailability(
            $this->hotel->id,
            now()->addDays(10)->format('Y-m-d'),
            now()->addDays(12)->format('Y-m-d')
        );

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $availableRooms);
    }

    public function test_generate_invoice(): void
    {
        $booking = Booking::factory()->create([
            'hotel_id' => $this->hotel->id,
            'guest_id' => $this->guest->id,
            'room_type_id' => $this->roomType->id,
            'room_rate' => 200.00,
            'nights' => 3,
            'total_amount' => 708.00,
            'tax_amount' => 108.00,
        ]);

        CheckIn::factory()->create([
            'booking_id' => $booking->id,
            'room_id' => $this->room->id,
            'guest_id' => $this->guest->id,
            'hotel_id' => $this->hotel->id,
        ]);

        $invoice = $this->bookingService->generateInvoice($booking->id);

        $this->assertNotNull($invoice);
        $this->assertEquals($booking->id, $invoice->booking_id);
    }
}
