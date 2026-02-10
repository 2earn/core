<?php

namespace Tests\Feature\Api\v2;

use App\Models\Platform;
use App\Models\BusinessSector;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for PlatformController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PlatformController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('platforms')]
class PlatformControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_get_paginated_platforms()
    {
        Platform::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/platforms/?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_filter_platforms_by_search()
    {
        Platform::factory()->create(['name' => 'Test Platform']);
        Platform::factory()->create(['name' => 'Other Platform']);

        $response = $this->getJson('/api/v2/platforms/?search=Test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_all_platforms()
    {
        Platform::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/platforms/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_enabled_platforms()
    {
        Platform::factory()->count(2)->create(['enabled' => true]);
        Platform::factory()->count(2)->create(['enabled' => false]);

        $response = $this->getJson('/api/v2/platforms/enabled');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_platform_by_id()
    {
        $platform = Platform::factory()->create(['name' => 'Test Platform']);

        $response = $this->getJson("/api/v2/platforms/{$platform->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_platform()
    {
        $response = $this->getJson('/api/v2/platforms/999999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_create_platform()
    {
        $businessSector = BusinessSector::factory()->create();

        $data = [
            'name' => 'New Platform',
            'business_sector_id' => $businessSector->id,
            'enabled' => true,
            'url' => 'https://example.com'
        ];

        $response = $this->postJson('/api/v2/platforms/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('platforms', ['name' => 'New Platform']);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_platform()
    {
        $response = $this->postJson('/api/v2/platforms/', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_platform()
    {
        $platform = Platform::factory()->create(['name' => 'Original Name']);

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v2/platforms/{$platform->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('platforms', [
            'id' => $platform->id,
            'name' => 'Updated Name'
        ]);
    }

    #[Test]
    public function it_can_delete_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->deleteJson("/api/v2/platforms/{$platform->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('platforms', ['id' => $platform->id]);
    }

    #[Test]
    public function it_can_get_platforms_with_user_purchases()
    {
        $response = $this->getJson('/api/v2/platforms/with-user-purchases');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_check_user_role_for_platform()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/platforms/{$platform->id}/check-user-role?user_id={$this->user->id}");

        $response->assertStatus(200);
    }
}

