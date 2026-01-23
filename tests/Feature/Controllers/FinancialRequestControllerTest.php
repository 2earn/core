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
    public function test_reset_outgoing_notification()
    {
        $this->markTestSkipped('Requires FinancialRequestService setup');
    }

    /** @test */
    public function test_reset_incoming_notification()
    {
        $this->markTestSkipped('Requires FinancialRequestService setup');
    }

    /** @test */
    public function test_reset_notifications_require_authentication()
    {
        $this->markTestSkipped('Requires authentication test');
    }

    /** @test */
    public function test_abort_404_when_user_not_found()
    {
        $this->markTestSkipped('Requires user validation');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
