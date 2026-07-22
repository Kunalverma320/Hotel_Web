<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Charge;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_hotel(): void
    {
        $hotel = Hotel::factory()->create();
        $booking = Booking::factory()->create(['hotel_id' => $hotel->id]);

        $this->assertInstanceOf(Hotel::class, $booking->hotel);
        $this->assertEquals($hotel->id, $booking->hotel->id);
    }

    public function test_belongs_to_room_type(): void
    {
        $roomType = RoomType::factory()->create();
        $booking = Booking::factory()->create(['room_type_id' => $roomType->id]);

        $this->assertInstanceOf(RoomType::class, $booking->roomType);
        $this->assertEquals($roomType->id, $booking->roomType->id);
    }

    public function test_belongs_to_guest(): void
    {
        $guest = Guest::factory()->create();
        $booking = Booking::factory()->create(['guest_id' => $guest->id]);

        $this->assertInstanceOf(Guest::class, $booking->guest);
        $this->assertEquals($guest->id, $booking->guest->id);
    }

    public function test_has_many_booking_rooms(): void
    {
        $booking = Booking::factory()->create();
        $bookingRoom = BookingRoom::factory()->create(['booking_id' => $booking->id]);

        $this->assertInstanceOf(BookingRoom::class, $booking->bookingRooms->first());
        $this->assertEquals(1, $booking->bookingRooms->count());
    }

    public function test_has_many_payments(): void
    {
        $booking = Booking::factory()->create();
        Payment::factory()->count(2)->create(['booking_id' => $booking->id]);

        $this->assertEquals(2, $booking->payments->count());
    }

    public function test_has_many_charges(): void
    {
        $booking = Booking::factory()->create();
        Charge::factory()->count(3)->create(['booking_id' => $booking->id]);

        $this->assertEquals(3, $booking->charges->count());
    }

    public function test_has_many_check_ins(): void
    {
        $booking = Booking::factory()->create();
        CheckIn::factory()->create(['booking_id' => $booking->id]);

        $this->assertEquals(1, $booking->checkIns->count());
    }

    public function test_has_many_check_outs(): void
    {
        $booking = Booking::factory()->create();
        CheckOut::factory()->create(['booking_id' => $booking->id]);

        $this->assertEquals(1, $booking->checkOuts->count());
    }

    public function test_scope_active(): void
    {
        Booking::factory()->create(['status' => 'pending']);
        Booking::factory()->create(['status' => 'confirmed']);
        Booking::factory()->create(['status' => 'checked_in']);
        Booking::factory()->create(['status' => 'cancelled']);
        Booking::factory()->create(['status' => 'checked_out']);

        $activeBookings = Booking::active()->get();

        $this->assertEquals(3, $activeBookings->count());
    }

    public function test_scope_by_hotel(): void
    {
        $hotelA = Hotel::factory()->create();
        $hotelB = Hotel::factory()->create();

        Booking::factory()->count(2)->create(['hotel_id' => $hotelA->id]);
        Booking::factory()->count(3)->create(['hotel_id' => $hotelB->id]);

        $this->assertEquals(2, Booking::byHotel($hotelA->id)->count());
        $this->assertEquals(3, Booking::byHotel($hotelB->id)->count());
    }

    public function test_scope_by_status(): void
    {
        Booking::factory()->count(2)->create(['status' => 'confirmed']);
        Booking::factory()->count(3)->create(['status' => 'pending']);

        $this->assertEquals(2, Booking::byStatus('confirmed')->count());
        $this->assertEquals(3, Booking::byStatus('pending')->count());
    }

    public function test_scope_confirmed(): void
    {
        Booking::factory()->count(2)->create(['status' => 'confirmed']);
        Booking::factory()->create(['status' => 'pending']);

        $this->assertEquals(2, Booking::confirmed()->count());
    }

    public function test_scope_checked_in(): void
    {
        Booking::factory()->count(3)->create(['status' => 'checked_in']);
        Booking::factory()->create(['status' => 'pending']);

        $this->assertEquals(3, Booking::checkedIn()->count());
    }

    public function test_scope_today_check_ins(): void
    {
        Booking::factory()->create(['check_in_date' => today(), 'status' => 'confirmed']);
        Booking::factory()->create(['check_in_date' => today()->subDay(), 'status' => 'confirmed']);

        $this->assertEquals(1, Booking::todayCheckIns()->count());
    }

    public function test_scope_today_check_outs(): void
    {
        Booking::factory()->create(['check_out_date' => today(), 'status' => 'checked_in']);
        Booking::factory()->create(['check_out_date' => today()->addDay(), 'status' => 'checked_in']);

        $this->assertEquals(1, Booking::todayCheckOuts()->count());
    }

    public function test_balance_attribute(): void
    {
        $booking = Booking::factory()->create([
            'total_amount' => 500.00,
            'paid_amount' => 200.00,
        ]);

        $this->assertEquals(300.00, $booking->balance);
    }

    public function test_balance_is_zero_when_fully_paid(): void
    {
        $booking = Booking::factory()->create([
            'total_amount' => 500.00,
            'paid_amount' => 500.00,
        ]);

        $this->assertEquals(0, $booking->balance);
    }

    public function test_booking_uses_soft_deletes(): void
    {
        $booking = Booking::factory()->create();
        $bookingId = $booking->id;
        $booking->delete();

        $this->assertNull(Booking::find($bookingId));
        $this->assertNotNull(Booking::withTrashed()->find($bookingId));
    }

    public function test_fillable_attributes(): void
    {
        $booking = new Booking();
        $fillable = $booking->getFillable();

        $this->assertContains('booking_number', $fillable);
        $this->assertContains('status', $fillable);
        $this->assertContains('total_amount', $fillable);
        $this->assertContains('check_in_date', $fillable);
        $this->assertContains('check_out_date', $fillable);
    }

    public function test_casting(): void
    {
        $booking = Booking::factory()->create([
            'check_in_date' => '2026-01-15',
            'check_out_date' => '2026-01-20',
            'total_amount' => 500.00,
            'adults' => 2,
            'metadata' => ['source' => 'website'],
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $booking->check_in_date);
        $this->assertIsFloat($booking->total_amount);
        $this->assertIsInt($booking->adults);
        $this->assertIsArray($booking->metadata);
    }
}
