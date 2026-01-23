<?php

/**
 * Test Suite for SharesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\SharesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Services\Balances\BalanceService;
use App\Services\Balances\ShareBalanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class SharesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $balanceService;
    protected $shareBalanceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->balanceService = Mockery::mock(BalanceService::class);
        $this->shareBalanceService = Mockery::mock(ShareBalanceService::class);
    }

    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_services_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->balanceService);
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->shareBalanceService);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\SharesController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SharesController::class, 'list'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SharesController::class, 'getSharesSolde'));
    }

    #[Test]
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
