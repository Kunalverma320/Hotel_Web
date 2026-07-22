<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Hotel $hotel;
    private RoomType $roomType;
    private Guest $guest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);

        $this->user = User::factory()->create();
        $this->hotel = Hotel::factory()->create();
        $this->roomType = RoomType::factory()->create(['hotel_id' => $this->hotel->id]);
        $this->guest = Guest::factory()->create();
    }

    public function test_index_page_loads(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.index'));

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_booking(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.store'), [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => now()->addDays(1)->format('Y-m-d'),
            'check_out_date' => now()->addDays(3)->format('Y-m-d'),
            'adults' => 2,
            'children' => 0,
            'special_requests' => 'Late check-in requested',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'guest_id' => $this->guest->id,
        ]);
    }

    public function test_store_creates_guest_when_not_provided(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.store'), [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'guest_first_name' => 'New',
            'guest_last_name' => 'Guest',
            'guest_email' => 'newguest@example.com',
            'guest_phone' => '+1-555-1234',
            'check_in_date' => now()->addDays(1)->format('Y-m-d'),
            'check_out_date' => now()->addDays(3)->format('Y-m-d'),
            'adults' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guests', ['email' => 'newguest@example.com']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.store'), []);

        $response->assertSessionHasErrors(['hotel_id', 'room_type_id', 'check_in_date', 'check_out_date', 'adults']);
    }

    public function test_show_page_loads(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.show', $booking->id));

        $response->assertStatus(200);
    }

    public function test_edit_page_loads(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.edit', $booking->id));

        $response->assertStatus(200);
    }

    public function test_update_modifies_booking(): void
    {
        $booking = Booking::factory()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->put(route('admin.bookings.update', $booking->id), [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'check_out_date' => now()->addDays(5)->format('Y-m-d'),
            'adults' => 3,
            'children' => 1,
        ]);

        $response->assertRedirect();
        $this->assertEquals(3, $booking->fresh()->adults);
    }

    public function test_confirm_changes_status(): void
    {
        $booking = Booking::factory()->pending()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.confirm', $booking->id));

        $response->assertRedirect();
        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->confirmed_at);
    }

    public function test_cancel_changes_status(): void
    {
        $booking = Booking::factory()->confirmed()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.cancel', $booking->id), [
            'cancellation_reason' => 'Guest requested cancellation',
        ]);

        $response->assertRedirect();
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->cancelled_at);
        $this->assertEquals('Guest requested cancellation', $booking->fresh()->cancellation_reason);
    }

    public function test_cancel_requires_reason(): void
    {
        $booking = Booking::factory()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.cancel', $booking->id), []);

        $response->assertSessionHasErrors('cancellation_reason');
    }

    public function test_no_show_changes_status(): void
    {
        $booking = Booking::factory()->confirmed()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.no-show', $booking->id));

        $response->assertRedirect();
        $this->assertEquals('no_show', $booking->fresh()->status);
    }

    public function test_index_filters_by_status(): void
    {
        Booking::factory()->pending()->create(['hotel_id' => $this->hotel->id]);
        Booking::factory()->confirmed()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.index', ['status' => 'confirmed']));

        $response->assertStatus(200);
    }

    public function test_index_filters_by_hotel(): void
    {
        Booking::factory()->count(3)->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.index', ['hotel_id' => $this->hotel->id]));

        $response->assertStatus(200);
    }

    public function test_index_searches_by_booking_number(): void
    {
        $booking = Booking::factory()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.index', ['search' => $booking->booking_number]));

        $response->assertStatus(200);
    }

    public function test_print_invoice_page_loads(): void
    {
        $booking = Booking::factory()->create(['hotel_id' => $this->hotel->id]);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.bookings.print-invoice', $booking->id));

        $response->assertStatus(200);
    }

    public function test_store_with_advance_payment(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.bookings.store'), [
            'hotel_id' => $this->hotel->id,
            'room_type_id' => $this->roomType->id,
            'guest_id' => $this->guest->id,
            'check_in_date' => now()->addDays(1)->format('Y-m-d'),
            'check_out_date' => now()->addDays(3)->format('Y-m-d'),
            'adults' => 2,
            'advance_amount' => 100,
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', ['amount' => 100, 'payment_method' => 'cash']);
    }

    public function test_guest_user_cannot_access_admin_bookings(): void
    {
        $response = $this->get(route('admin.bookings.index'));

        $response->assertRedirect(route('login'));
    }
}
