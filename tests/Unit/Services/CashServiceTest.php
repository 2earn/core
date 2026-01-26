<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\CashService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CashService $cashService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cashService = new CashService();
    }

    /**
     * Test preparing cash to BFS exchange successfully
     */
    public function test_prepare_cash_to_bfs_exchange_generates_otp()
    {
        // Arrange
        $user = User::factory()->create();
        $fullNumber = '+1234567890';

        // Act
        $result = $this->cashService->prepareCashToBfsExchange($user->id, $fullNumber);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('otpCode', $result);
        $this->assertIsString($result['otpCode']);
        $this->assertEquals(4, strlen($result['otpCode']));
        $this->assertTrue($result['shouldNotifyBySms']);

        // Verify OTP was saved to user
        $user->refresh();
        $this->assertEquals($result['otpCode'], $user->activationCodeValue);
    }

    /**
     * Test preparing cash exchange includes verification params
     */
    public function test_prepare_cash_to_bfs_exchange_includes_verification_params()
    {
        // Arrange
        $user = User::factory()->create();
        $fullNumber = '+1234567890';

        // Act
        $result = $this->cashService->prepareCashToBfsExchange($user->id, $fullNumber);

        // Assert
        $this->assertArrayHasKey('verificationParams', $result);
        $this->assertEquals('warning', $result['verificationParams']['type']);
        $this->assertEquals('Opt', $result['verificationParams']['title']);
        $this->assertEquals($fullNumber, $result['verificationParams']['FullNumber']);
    }

    /**
     * Test verifying cash to BFS exchange with correct OTP
     */
    public function test_verify_cash_to_bfs_exchange_succeeds_with_correct_otp()
    {
        // Arrange
        $user = User::factory()->create(['activationCodeValue' => '1234']);
        $code = '1234';
        $storedOtp = '1234';

        // Act
        $result = $this->cashService->verifyCashToBfsExchange($user->id, $code, $storedOtp);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('OTP verified successfully', $result['message']);
    }

    /**
     * Test verifying cash to BFS exchange with incorrect OTP
     */
    public function test_verify_cash_to_bfs_exchange_fails_with_incorrect_otp()
    {
        // Arrange
        $user = User::factory()->create(['activationCodeValue' => '1234']);
        $code = '5678'; // Wrong code
        $storedOtp = '1234';

        // Act
        $result = $this->cashService->verifyCashToBfsExchange($user->id, $code, $storedOtp);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid OPT code', $result['message']);
    }

    /**
     * Test verifying exchange with empty OTP
     */
    public function test_verify_cash_to_bfs_exchange_fails_with_empty_otp()
    {
        // Arrange
        $user = User::factory()->create(['activationCodeValue' => '1234']);
        $code = '';
        $storedOtp = '1234';

        // Act
        $result = $this->cashService->verifyCashToBfsExchange($user->id, $code, $storedOtp);

        // Assert
        $this->assertFalse($result['success']);
    }
}
