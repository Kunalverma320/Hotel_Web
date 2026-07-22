<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\GuestDocument;
use App\Models\GuestPreference;
use App\Models\LoyaltyTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_many_bookings(): void
    {
        $guest = Guest::factory()->create();
        Booking::factory()->count(3)->create(['guest_id' => $guest->id]);

        $this->assertEquals(3, $guest->bookings->count());
        $this->assertInstanceOf(Booking::class, $guest->bookings->first());
    }

    public function test_has_many_documents(): void
    {
        $guest = Guest::factory()->create();
        $document = GuestDocument::factory()->create(['guest_id' => $guest->id]);

        $this->assertEquals(1, $guest->documents->count());
        $this->assertInstanceOf(GuestDocument::class, $guest->documents->first());
    }

    public function test_has_many_preferences(): void
    {
        $guest = Guest::factory()->create();
        GuestPreference::factory()->count(2)->create(['guest_id' => $guest->id]);

        $this->assertEquals(2, $guest->preferences->count());
    }

    public function test_has_many_loyalty_transactions(): void
    {
        $guest = Guest::factory()->create();
        LoyaltyTransaction::factory()->count(3)->create(['guest_id' => $guest->id]);

        $this->assertEquals(3, $guest->loyaltyTransactions->count());
    }

    public function test_scope_blacklisted(): void
    {
        Guest::factory()->create(['is_blacklisted' => true]);
        Guest::factory()->count(3)->create(['is_blacklisted' => false]);

        $this->assertEquals(1, Guest::blacklisted()->count());
    }

    public function test_scope_by_loyalty_tier(): void
    {
        Guest::factory()->create(['loyalty_tier' => 'platinum']);
        Guest::factory()->count(2)->create(['loyalty_tier' => 'gold']);

        $this->assertEquals(1, Guest::byLoyaltyTier('platinum')->count());
        $this->assertEquals(2, Guest::byLoyaltyTier('gold')->count());
    }

    public function test_full_name_attribute(): void
    {
        $guest = Guest::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $guest->full_name);
    }

    public function test_full_name_without_last_name(): void
    {
        $guest = Guest::factory()->create([
            'first_name' => 'John',
            'last_name' => '',
        ]);

        $this->assertEquals('John', $guest->full_name);
    }

    public function test_uses_soft_deletes(): void
    {
        $guest = Guest::factory()->create();
        $guestId = $guest->id;
        $guest->delete();

        $this->assertNull(Guest::find($guestId));
        $this->assertNotNull(Guest::withTrashed()->find($guestId));
    }

    public function test_fillable_attributes(): void
    {
        $guest = new Guest();
        $fillable = $guest->getFillable();

        $this->assertContains('first_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('loyalty_points', $fillable);
        $this->assertContains('loyalty_tier', $fillable);
    }

    public function test_casting(): void
    {
        $guest = Guest::factory()->create([
            'is_blacklisted' => true,
            'loyalty_points' => 100,
            'total_spent' => 5000.00,
            'date_of_birth' => '1990-01-15',
            'metadata' => ['preferred_floor' => 'high'],
        ]);

        $this->assertIsBool($guest->is_blacklisted);
        $this->assertIsInt($guest->loyalty_points);
        $this->assertIsFloat($guest->total_spent);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $guest->date_of_birth);
        $this->assertIsArray($guest->metadata);
    }

    public function test_vip_guest_high_loyalty_points(): void
    {
        $guest = Guest::factory()->vip()->create();

        $this->assertGreaterThanOrEqual(5000, $guest->loyalty_points);
        $this->assertEquals('platinum', $guest->loyalty_tier);
        $this->assertGreaterThanOrEqual(20, $guest->total_stays);
        $this->assertGreaterThanOrEqual(10000, $guest->total_spent);
    }

    public function test_loyalty_points_default_zero(): void
    {
        $guest = Guest::factory()->create(['loyalty_points' => 0]);

        $this->assertEquals(0, $guest->loyalty_points);
    }
}
