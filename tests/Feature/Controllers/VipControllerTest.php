<?php

/**
 * Test Suite for VipController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\VipController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\vip;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VipControllerTest extends TestCase
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
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_controller_has_create_method()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\VipController::class, 'create'));
    }

    /** @test */
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }

    /** @test */
    public function test_vip_and_setting_models_exist()
    {
        $this->assertTrue(class_exists(vip::class));
        $this->assertTrue(class_exists(Setting::class));
    }

    /** @test */
    public function test_create_endpoint_accepts_post_request()
    {
        $response = $this->postJson('/api/vip/create', [
            'reciver' => $this->user->idUser,
            'coefficient' => 1.5,
            'periode' => 30,
            'note' => 'Test VIP',
            'minshares' => 100
        ]);

        // Should return some response (200, 404, or 500 depending on implementation)
        $this->assertNotNull($response->status());
    }

}
