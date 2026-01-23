<?php

/**
 * Test Suite for UserssController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\UserssController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Services\Balances\BalanceService;
use App\Services\Balances\BalanceTreeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class UserssControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $balanceService;
    protected $treeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->balanceService = Mockery::mock(BalanceService::class);
        $this->treeService = Mockery::mock(BalanceTreeService::class);
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
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->treeService);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\UserssController::class, 'invitations'));
        $this->assertTrue(method_exists(\App\Http\Controllers\UserssController::class, 'getPurchaseBFSUser'));
        $this->assertTrue(method_exists(\App\Http\Controllers\UserssController::class, 'getTreeUser'));
        $this->assertTrue(method_exists(\App\Http\Controllers\UserssController::class, 'getSmsUser'));
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
