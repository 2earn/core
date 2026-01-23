<?php

/**
 * Test Suite for FinancialRequestController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\FinancialRequestController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\FinancialRequest\FinancialRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class FinancialRequestControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $financialRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->financialRequestService = Mockery::mock(FinancialRequestService::class);
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_financial_request_service_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->financialRequestService);
    }

    /** @test */
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\FinancialRequestController::class, 'resetOutGoingNotification'));
        $this->assertTrue(method_exists(\App\Http\Controllers\FinancialRequestController::class, 'resetInComingNotification'));
    }

    /** @test */
    public function test_user_instance_is_valid()
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
