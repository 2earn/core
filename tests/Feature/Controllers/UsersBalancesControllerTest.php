<?php

/**
 * Test Suite for UsersBalancesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\UsersBalancesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\Balances\BalanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class UsersBalancesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $balanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->balanceService = Mockery::mock(BalanceService::class);
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_balance_service_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->balanceService);
    }

    /** @test */
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UsersBalancesController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\UsersBalancesController::class, 'list'));
        $this->assertTrue(method_exists(\App\Http\Controllers\UsersBalancesController::class, 'getUserCashBalance'));
    }

    /** @test */
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
