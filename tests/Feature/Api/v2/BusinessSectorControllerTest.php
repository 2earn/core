<?php

namespace Tests\Feature\Api\v2;

use App\Models\BusinessSector;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for BusinessSectorController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\BusinessSectorController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('business_sectors')]
class BusinessSectorControllerTest extends TestCase
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
    public function it_can_get_paginated_business_sectors()
    {
        BusinessSector::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/business-sectors/');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_all_business_sectors()
    {
        BusinessSector::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/business-sectors/all');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_ordered_business_sectors()
    {
        BusinessSector::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/business-sectors/ordered');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_business_sector_by_id()
    {
        $sector = BusinessSector::factory()->create(['name' => 'Test Sector']);

        $response = $this->getJson("/api/v2/business-sectors/{$sector->id}");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_business_sector_with_images()
    {
        $sector = BusinessSector::factory()->create();

        $response = $this->getJson("/api/v2/business-sectors/{$sector->id}/with-images");

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_create_business_sector()
    {
        $data = [
            'name' => 'New Sector',
            'description' => 'Test Description'
        ];

        $response = $this->postJson('/api/v2/business-sectors/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('business_sectors', ['name' => 'New Sector']);
    }

    #[Test]
    public function it_can_update_business_sector()
    {
        $sector = BusinessSector::factory()->create(['name' => 'Original Name']);

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/v2/business-sectors/{$sector->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('business_sectors', [
            'id' => $sector->id,
            'name' => 'Updated Name'
        ]);
    }

    #[Test]
    public function it_can_delete_business_sector()
    {
        $sector = BusinessSector::factory()->create();

        $response = $this->deleteJson("/api/v2/business-sectors/{$sector->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('business_sectors', ['id' => $sector->id]);
    }

    #[Test]
    public function it_can_get_user_purchases_by_business_sector()
    {
        $response = $this->getJson("/api/v2/business-sectors/user-purchases?user_id={$this->user->id}");

        $response->assertStatus(200);
    }
}

