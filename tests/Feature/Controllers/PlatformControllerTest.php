<?php

/**
 * Test Suite for PlatformController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\PlatformController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlatformControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_index_returns_datatables()
    {
        Platform::factory()->count(3)->create();

        $response = $this->getJson('/api/platforms/datatables');

        // Datatables endpoints typically return JSON
        $response->assertStatus(200);
    }

    /** @test */
    public function test_platforms_can_be_created()
    {
        $platform = Platform::factory()->create();

        $this->assertDatabaseHas('platforms', [
            'id' => $platform->id,
            'name' => $platform->name
        ]);
    }

    /** @test */
    public function test_platform_has_required_attributes()
    {
        $platform = Platform::factory()->create();

        $this->assertNotNull($platform->id);
        $this->assertNotNull($platform->name);
        $this->assertNotNull($platform->created_at);
    }
}
