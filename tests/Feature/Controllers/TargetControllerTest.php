<?php

/**
 * Test Suite for TargetController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\TargetController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Target;
use App\Services\Targeting\Targeting;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TargetControllerTest extends TestCase
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
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_controller_has_get_target_data_method()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\TargetController::class, 'getTargetData'));
    }

    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }

    #[Test]
    public function test_target_class_exists()
    {
        $this->assertTrue(class_exists(Target::class));
    }

}
