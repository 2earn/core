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

    /** @test */
    public function test_list_returns_share_balances()
    {
        $this->markTestSkipped('Requires ShareBalanceService');
    }

    /** @test */
    public function test_index_returns_action_history_datatables()
    {
        $this->markTestSkipped('Requires action_history table');
    }

    /** @test */
    public function test_get_shares_solde_returns_user_shares()
    {
        $this->markTestSkipped('Requires share balance data');
    }

    /** @test */
    public function test_get_shares_soldes_includes_calculations()
    {
        $this->markTestSkipped('Requires price calculations');
    }

    /** @test */
    public function test_get_share_price_evolution_returns_json()
    {
        $this->markTestSkipped('Requires price evolution data');
    }

    /** @test */
    public function test_share_price_evolution_formats_values()
    {
        $this->markTestSkipped('Requires float conversion');
    }

    /** @test */
    public function test_get_share_price_evolution_by_day()
    {
        $this->markTestSkipped('Requires daily price data');
    }

    /** @test */
    public function test_get_share_price_evolution_by_week()
    {
        $this->markTestSkipped('Requires weekly price data');
    }

    /** @test */
    public function test_get_share_price_evolution_by_month()
    {
        $this->markTestSkipped('Requires monthly price data');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
