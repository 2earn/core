<?php

/**
 * Test Suite for BalancesOperationsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\BalancesOperationsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BalancesOperationsControllerTest extends TestCase
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
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getCategories'));
    }

    #[Test]
    public function test_user_factory_works()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }
}
