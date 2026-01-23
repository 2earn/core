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
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function test_platforms_can_be_created_using_factory()
    {
        $platforms = Platform::factory()->count(3)->create();

        $this->assertCount(3, $platforms);
        foreach ($platforms as $platform) {
            $this->assertDatabaseHas('platforms', ['id' => $platform->id]);
        }
    }

    #[Test]
    public function test_platforms_can_be_created()
    {
        $platform = Platform::factory()->create();

        $this->assertDatabaseHas('platforms', [
            'id' => $platform->id,
            'name' => $platform->name
        ]);
    }

    #[Test]
    public function test_platform_has_required_attributes()
    {
        $platform = Platform::factory()->create();

        $this->assertNotNull($platform->id);
        $this->assertNotNull($platform->name);
        $this->assertNotNull($platform->created_at);
    }
}
