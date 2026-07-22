<?php

namespace Tests\Feature\Admin;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);

        $this->user = User::factory()->create();
    }

    public function test_index_page_loads(): void
    {
        Guest::factory()->count(5)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.index'));

        $response->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.create'));

        $response->assertStatus(200);
    }

    public function test_store_creates_guest(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.guests.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1-555-1234',
            'nationality' => 'US',
            'city' => 'New York',
            'country' => 'USA',
        ]);

        $response->assertRedirect(route('admin.guests.index'));
        $this->assertDatabaseHas('guests', ['email' => 'john.doe@example.com']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.guests.store'), []);

        $response->assertSessionHasErrors(['first_name', 'last_name']);
    }

    public function test_show_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.show', $guest->id));

        $response->assertStatus(200);
    }

    public function test_edit_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.edit', $guest->id));

        $response->assertStatus(200);
    }

    public function test_update_modifies_guest(): void
    {
        $guest = Guest::factory()->create(['first_name' => 'Old']);

        $this->actingAs($this->user);

        $response = $this->put(route('admin.guests.update', $guest->id), [
            'first_name' => 'Updated',
            'last_name' => $guest->last_name,
            'email' => $guest->email,
            'phone' => $guest->phone,
        ]);

        $response->assertRedirect();
        $this->assertEquals('Updated', $guest->fresh()->first_name);
    }

    public function test_destroy_deletes_guest(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->delete(route('admin.guests.destroy', $guest->id));

        $response->assertRedirect(route('admin.guests.index'));
        $this->assertSoftDeleted($guest);
    }

    public function test_index_searches_by_name(): void
    {
        Guest::factory()->create(['first_name' => 'UniqueName']);
        Guest::factory()->count(3)->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.index', ['search' => 'UniqueName']));

        $response->assertStatus(200);
    }

    public function test_index_filters_by_nationality(): void
    {
        Guest::factory()->create(['nationality' => 'US']);
        Guest::factory()->create(['nationality' => 'GB']);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.index', ['nationality' => 'US']));

        $response->assertStatus(200);
    }

    public function test_index_filters_by_loyalty_tier(): void
    {
        Guest::factory()->create(['loyalty_tier' => 'platinum']);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.index', ['loyalty_tier' => 'platinum']));

        $response->assertStatus(200);
    }

    public function test_index_filters_blacklisted(): void
    {
        Guest::factory()->blacklisted()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.index', ['is_blacklisted' => 1]));

        $response->assertStatus(200);
    }

    public function test_blacklist_toggles_status(): void
    {
        $guest = Guest::factory()->create(['is_blacklisted' => false]);

        $this->actingAs($this->user);

        $response = $this->post(route('admin.guests.blacklist', $guest->id), ['reason' => 'Damaged property']);

        $response->assertRedirect();
        $this->assertTrue((bool) $guest->fresh()->is_blacklisted);
    }

    public function test_documents_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.documents', $guest->id));

        $response->assertStatus(200);
    }

    public function test_preferences_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.preferences', $guest->id));

        $response->assertStatus(200);
    }

    public function test_history_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.history', $guest->id));

        $response->assertStatus(200);
    }

    public function test_loyalty_page_loads(): void
    {
        $guest = Guest::factory()->create();

        $this->actingAs($this->user);

        $response = $this->get(route('admin.guests.loyalty', $guest->id));

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_guests(): void
    {
        $response = $this->get(route('admin.guests.index'));

        $response->assertRedirect(route('login'));
    }
}
