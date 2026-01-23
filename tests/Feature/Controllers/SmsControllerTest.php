<?php

/**
 * Test Suite for SmsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\SmsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Services\sms\SmsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class SmsControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $smsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->smsService = Mockery::mock(SmsService::class);
    }

    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_sms_service_can_be_mocked()
    {
        $this->assertInstanceOf(\Mockery\MockInterface::class, $this->smsService);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\SmsController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SmsController::class, 'getSmsData'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SmsController::class, 'show'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SmsController::class, 'getStatistics'));
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
