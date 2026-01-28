<?php

namespace Tests\Unit\Services;

use App\Services\VipService;
use Tests\TestCase;

class VipServiceTest extends TestCase
{

    protected VipService $vipService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vipService = new VipService();
    }

    /**
     * Test getActiveVipByUserId returns active VIP
     */
    public function test_get_active_vip_by_user_id_returns_active_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $vip = \App\Models\vip::factory()->active()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->getActiveVipByUserId($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\App\Models\vip::class, $result);
        $this->assertEquals($vip->id, $result->id);
    }

    /**
     * Test getActiveVipByUserId returns null when no active VIP
     */
    public function test_get_active_vip_by_user_id_returns_null_when_no_active_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\vip::factory()->closed()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->getActiveVipByUserId($user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getActiveVipsByUserId returns collection of active VIPs
     */
    public function test_get_active_vips_by_user_id_returns_collection()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\vip::factory()->active()->count(2)->create(['idUser' => $user->id]);
        \App\Models\vip::factory()->closed()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->getActiveVipsByUserId($user->id);

        // Assert
        $this->assertCount(2, $result);
    }

    /**
     * Test closeVip closes active VIP
     */
    public function test_close_vip_closes_active_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $vip = \App\Models\vip::factory()->active()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->closeVip($user->id);

        // Assert
        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->closed);
        $this->assertNotNull($vip->closedDate);
    }

    /**
     * Test closeVip returns false when no active VIP
     */
    public function test_close_vip_returns_false_when_no_active_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->vipService->closeVip($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test declenchVip declenches active VIP
     */
    public function test_declench_vip_declenches_active_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $vip = \App\Models\vip::factory()->active()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->declenchVip($user->id);

        // Assert
        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->declenched);
        $this->assertNotNull($vip->declenchedDate);
    }

    /**
     * Test declenchAndCloseVip declenches and closes VIP
     */
    public function test_declench_and_close_vip_declenches_and_closes()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $vip = \App\Models\vip::factory()->active()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->declenchAndCloseVip($user->id);

        // Assert
        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->closed);
        $this->assertEquals(1, $vip->declenched);
        $this->assertNotNull($vip->closedDate);
        $this->assertNotNull($vip->declenchedDate);
    }

    /**
     * Test hasActiveVip returns true when user has active VIP
     */
    public function test_has_active_vip_returns_true_when_active()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\vip::factory()->active()->create(['idUser' => $user->id]);

        // Act
        $result = $this->vipService->hasActiveVip($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasActiveVip returns false when user has no active VIP
     */
    public function test_has_active_vip_returns_false_when_not_active()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->vipService->hasActiveVip($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test isVipValid returns true for valid VIP
     */
    public function test_is_vip_valid_returns_true_for_valid_vip()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48, // 48 hours deadline
        ]);

        // Act
        $result = $this->vipService->isVipValid($vip);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test isVipValid returns false for expired VIP
     */
    public function test_is_vip_valid_returns_false_for_expired_vip()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'dateFNS' => now()->subDays(10),
            'flashDeadline' => 24, // 24 hours deadline
        ]);

        // Act
        $result = $this->vipService->isVipValid($vip);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test calculateVipActions returns calculated actions
     */
    public function test_calculate_vip_actions_returns_calculated_value()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'solde' => 100,
            'flashCoefficient' => 2.0,
        ]);

        // Act
        $result = $this->vipService->calculateVipActions($vip, 500, 100, 1.5);

        // Assert
        $this->assertIsInt($result);
    }

    /**
     * Test calculateVipBenefits returns calculated benefits
     */
    public function test_calculate_vip_benefits_returns_calculated_value()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create(['solde' => 100]);

        // Act
        $result = $this->vipService->calculateVipBenefits($vip, 50, 1.5);

        // Assert
        $this->assertIsFloat($result);
        $this->assertEquals(75.0, $result); // (100 - 50) * 1.5
    }

    /**
     * Test calculateVipCost returns calculated cost
     */
    public function test_calculate_vip_cost_returns_calculated_value()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'flashCoefficient' => 2.0,
        ]);

        // Act
        $result = $this->vipService->calculateVipCost($vip, 100, 1.5);

        // Assert
        $this->assertIsFloat($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    /**
     * Test getVipFlashStatus returns status array
     */
    public function test_get_vip_flash_status_returns_status_array()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
            'flashCoefficient' => 2.0,
            'flashMinAmount' => 10,
        ]);

        // Act
        $result = $this->vipService->getVipFlashStatus($vip);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('isFlashActive', $result);
        $this->assertArrayHasKey('flashEndDate', $result);
        $this->assertArrayHasKey('flashTimes', $result);
        $this->assertArrayHasKey('flashPeriod', $result);
        $this->assertArrayHasKey('flashMinShares', $result);
        $this->assertTrue($result['isFlashActive']);
    }

    /**
     * Test getVipCalculations returns complete calculations
     */
    public function test_get_vip_calculations_returns_complete_calculations()
    {
        // Arrange
        $vip = \App\Models\vip::factory()->create([
            'solde' => 100,
            'flashCoefficient' => 2.0,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        // Act
        $result = $this->vipService->getVipCalculations($vip, 500, 100, 1.5, 1.0);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('actions', $result);
        $this->assertArrayHasKey('benefits', $result);
        $this->assertArrayHasKey('cost', $result);
        $this->assertArrayHasKey('flashStatus', $result);
    }

    /**
     * Test hasActiveFlashVip returns true when active flash VIP exists
     */
    public function test_has_active_flash_vip_returns_true_when_exists()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\vip::factory()->active()->create([
            'idUser' => $user->id,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        // Act
        $result = $this->vipService->hasActiveFlashVip($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasActiveFlashVip returns false when no active flash VIP
     */
    public function test_has_active_flash_vip_returns_false_when_not_exists()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->vipService->hasActiveFlashVip($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getVipStatusForUser returns VIP status for user
     */
    public function test_get_vip_status_for_user_returns_status()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\vip::factory()->active()->create([
            'idUser' => $user->id,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        // Act
        $result = $this->vipService->getVipStatusForUser($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('vip', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('isActive', $result);
    }

    /**
     * Test getVipStatusForUser returns null when no VIP
     */
    public function test_get_vip_status_for_user_returns_null_when_no_vip()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->vipService->getVipStatusForUser($user->id);

        // Assert
        $this->assertNull($result);
    }
}
