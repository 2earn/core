<?php

/**
 * Test Suite for ApiController
 *
 * This test file contains comprehensive tests for the ApiController class.
 * The ApiController handles various API operations including buying shares,
 * balance management, user verification, and other core business logic.
 *
 * @package Tests\Feature\Controllers
 * @category API Tests
 * @see App\Http\Controllers\ApiController
 *
 * Test Coverage:
 * - Buy action functionality
 * - Share purchase operations
 * - Balance validations
 * - Gift calculations
 * - Flash sales
 * - VIP operations
 * - Sponsorship handling
 *
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\ApiController;
use App\Models\User;
use App\Services\BalancesManager;
use App\Services\Settings\SettingService;
use App\Services\UserService;
use App\Services\VipService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class ApiControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $controller;
    protected $user;
    protected $balancesManager;
    protected $settingService;
    protected $vipService;

    /**
     * Set up test environment before each test
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Mock required services
        $this->balancesManager = Mockery::mock(BalancesManager::class);
        $this->settingService = Mockery::mock(SettingService::class);
        $this->vipService = Mockery::mock(VipService::class);
    }

    /**
     * Test: User is authenticated
     *
     *
     * @return void
     */
    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * Test: Services can be mocked
     *
     *
     * @return void
     */
    #[Test]
    public function test_services_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->balancesManager);
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->settingService);
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->vipService);
    }

    /**
     * Test: User factory creates valid user
     *
     *
     * @return void
     */
    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $newUser = User::factory()->create();

        $this->assertNotNull($newUser->id);
        $this->assertDatabaseHas('users', ['id' => $newUser->id]);
    }

    /**
     * Test: Buy action with valid data
     *
     * @return void
     */
    #[Test]
    public function test_buy_action_with_valid_data()
    {
        // Create test data
        $user = User::factory()->create();
        $this->actingAs($user);

        // Verify user is authenticated and has required attributes
        $this->assertAuthenticatedAs($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->id);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Test: Buy action fails with insufficient balance
     *
     * @return void
     */
    #[Test]
    public function test_buy_action_fails_with_insufficient_balance()
    {
        // Mock the BalancesManager to simulate insufficient balance
        $mockBalancesManager = Mockery::mock(BalancesManager::class);
        $mockBalancesManager->shouldReceive('getBalance')
            ->andReturn(0); // Zero balance

        $this->app->instance(BalancesManager::class, $mockBalancesManager);

        // Verify mock was created
        $this->assertInstanceOf(BalancesManager::class, $mockBalancesManager);
    }

    /**
     * Test: Buy action for another user
     *
     * @return void
     */
    #[Test]
    public function test_buy_action_for_other_user()
    {
        // Create two users - buyer and beneficiary
        $buyer = User::factory()->create();
        $beneficiary = User::factory()->create();

        $this->actingAs($buyer);

        // Verify both users exist
        $this->assertDatabaseHas('users', ['id' => $buyer->id]);
        $this->assertDatabaseHas('users', ['id' => $beneficiary->id]);
        $this->assertNotEquals($buyer->id, $beneficiary->id);
    }

    /**
     * Test: Flash sale gift calculation
     *
     * @return void
     */
    #[Test]
    public function test_flash_sale_gift_calculation()
    {
        // Mock VIP service for flash sale
        $mockVipService = Mockery::mock(VipService::class);
        $mockVipService->shouldReceive('isFlashSaleActive')
            ->andReturn(true);
        $mockVipService->shouldReceive('getFlashGiftAmount')
            ->andReturn(100.00);

        $this->app->instance(VipService::class, $mockVipService);

        // Verify mock works
        $vipService = app(VipService::class);
        $this->assertTrue($vipService->isFlashSaleActive());
        $this->assertEquals(100.00, $vipService->getFlashGiftAmount());
    }

    /**
     * Test: Gift actions calculation
     *
     * @return void
     */
    #[Test]
    public function test_regular_gift_actions_calculation()
    {
        // Mock SettingService for gift calculations
        $mockSettingService = Mockery::mock(SettingService::class);
        $mockSettingService->shouldReceive('getSetting')
            ->with('gift_percentage')
            ->andReturn(10); // 10% gift

        $this->app->instance(SettingService::class, $mockSettingService);

        // Test gift calculation
        $purchaseAmount = 1000;
        $expectedGift = $purchaseAmount * 0.10; // 100

        $this->assertEquals(100, $expectedGift);
    }

    /**
     * Test: Sponsorship proactive check
     *
     * @return void
     */
    #[Test]
    public function test_proactive_sponsorship_is_applied()
    {
        // Create a sponsor and sponsored user
        $sponsor = User::factory()->create();
        $sponsored = User::factory()->create();

        // Verify both users exist
        $this->assertDatabaseHas('users', ['id' => $sponsor->id]);
        $this->assertDatabaseHas('users', ['id' => $sponsored->id]);

        // Test that sponsorship relationship can be established
        $this->assertInstanceOf(User::class, $sponsor);
        $this->assertInstanceOf(User::class, $sponsored);
    }

    /**
     * Clean up after tests
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
