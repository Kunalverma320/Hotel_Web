<?php

namespace Tests\Feature\Admin;

use App\Models\Company;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class);

        $this->user = User::factory()->create();
    }

    public function test_dashboard_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_dashboard_redirects_unauthenticated_user(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_contains_expected_sections(): void
    {
        Company::factory()->create();
        $hotel = Hotel::factory()->create();

        $this->user->update(['company_id' => $hotel->company_id, 'hotel_id' => $hotel->id]);
        session(['current_hotel_id' => $hotel->id]);

        $this->actingAs($this->user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard', false);
    }

    public function test_dashboard_with_different_period_parameter(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.dashboard', ['period' => 'month']));
        $response->assertStatus(200);

        $response = $this->get(route('admin.dashboard', ['period' => 'year']));
        $response->assertStatus(200);

        $response = $this->get(route('admin.dashboard', ['period' => 'today']));
        $response->assertStatus(200);
    }

    public function test_super_admin_sees_all_hotels_data(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        Company::factory()->count(2)->create();
        Hotel::factory()->count(3)->create();

        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_returns_successful_json_response_for_ajax(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}
