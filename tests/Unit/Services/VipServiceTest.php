<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\vip;
use App\Services\VipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VipServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VipService $vipService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vipService = new VipService();
    }

    public function test_get_active_vip_by_user_id_works()
    {
        $user = User::factory()->create();
        $activeVip = vip::factory()->active()->create(['idUser' => $user->idUser]);
        vip::factory()->closed()->create(['idUser' => $user->idUser]);

        $result = $this->vipService->getActiveVipByUserId($user->idUser);

        $this->assertNotNull($result);
        $this->assertInstanceOf(vip::class, $result);
        $this->assertEquals($activeVip->id, $result->id);
        $this->assertFalse($result->closed);
    }

    public function test_get_active_vips_by_user_id_works()
    {
        $user = User::factory()->create();
        vip::factory()->active()->count(3)->create(['idUser' => $user->idUser]);
        vip::factory()->closed()->count(2)->create(['idUser' => $user->idUser]);

        $result = $this->vipService->getActiveVipsByUserId($user->idUser);

        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($v) => !$v->closed));
    }

    public function test_close_vip_works()
    {
        $user = User::factory()->create();
        $vip = vip::factory()->active()->create(['idUser' => $user->idUser]);

        $result = $this->vipService->closeVip($user->idUser);

        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->closed);
        $this->assertNotNull($vip->closedDate);
    }

    public function test_declench_vip_works()
    {
        $user = User::factory()->create();
        $vip = vip::factory()->active()->create(['idUser' => $user->idUser]);

        $result = $this->vipService->declenchVip($user->idUser);

        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->declenched);
        $this->assertNotNull($vip->declenchedDate);
    }

    public function test_declench_and_close_vip_works()
    {
        $user = User::factory()->create();
        $vip = vip::factory()->active()->create(['idUser' => $user->idUser]);

        $result = $this->vipService->declenchAndCloseVip($user->idUser);

        $this->assertTrue($result);
        $vip->refresh();
        $this->assertEquals(1, $vip->closed);
        $this->assertEquals(1, $vip->declenched);
        $this->assertNotNull($vip->closedDate);
        $this->assertNotNull($vip->declenchedDate);
    }

    public function test_has_active_vip_works()
    {
        $userWithVip = User::factory()->create();
        $userWithoutVip = User::factory()->create();
        vip::factory()->active()->create(['idUser' => $userWithVip->idUser]);

        $hasVip = $this->vipService->hasActiveVip($userWithVip->idUser);
        $noVip = $this->vipService->hasActiveVip($userWithoutVip->idUser);

        $this->assertTrue($hasVip);
        $this->assertFalse($noVip);
    }

    public function test_is_vip_valid_works()
    {
        $validVip = vip::factory()->create([
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);
        $expiredVip = vip::factory()->create([
            'dateFNS' => now()->subHours(72),
            'flashDeadline' => 48,
        ]);

        $isValid = $this->vipService->isVipValid($validVip);
        $isExpired = $this->vipService->isVipValid($expiredVip);

        $this->assertTrue($isValid);
        $this->assertFalse($isExpired);
    }

    public function test_calculate_vip_actions_works()
    {
        $vip = vip::factory()->create([
            'solde' => 100,
            'flashCoefficient' => 2.5,
        ]);

        $result = $this->vipService->calculateVipActions($vip, 50, 200, 1.5);

        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function test_calculate_vip_benefits_works()
    {
        $vip = vip::factory()->create(['solde' => 100]);
        $actions = 40;
        $actualActionValue = 2.5;

        $result = $this->vipService->calculateVipBenefits($vip, $actions, $actualActionValue);

        $this->assertEquals(150.0, $result);
        $this->assertIsFloat($result);
    }

    public function test_calculate_vip_cost_works()
    {
        $vip = vip::factory()->create(['flashCoefficient' => 2.0]);
        $actions = 50;
        $actualActionValue = 3.0;

        $result = $this->vipService->calculateVipCost($vip, $actions, $actualActionValue);

        $this->assertIsFloat($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function test_get_vip_flash_status_works()
    {
        $vip = vip::factory()->create([
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
            'flashCoefficient' => 2.5,
            'flashMinAmount' => 10,
        ]);

        $result = $this->vipService->getVipFlashStatus($vip);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('isFlashActive', $result);
        $this->assertArrayHasKey('flashEndDate', $result);
        $this->assertArrayHasKey('flashTimes', $result);
        $this->assertArrayHasKey('flashPeriod', $result);
        $this->assertArrayHasKey('flashMinShares', $result);
        $this->assertTrue($result['isFlashActive']);
        $this->assertEquals(2.5, $result['flashTimes']);
    }

    public function test_get_vip_calculations_works()
    {
        $vip = vip::factory()->create([
            'solde' => 100,
            'flashCoefficient' => 2.0,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        $result = $this->vipService->getVipCalculations($vip, 50, 200, 1.5, 2.5);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('actions', $result);
        $this->assertArrayHasKey('benefits', $result);
        $this->assertArrayHasKey('cost', $result);
        $this->assertArrayHasKey('flashStatus', $result);
    }

    public function test_has_active_flash_vip_works()
    {
        $user = User::factory()->create();
        vip::factory()->create([
            'idUser' => $user->idUser,
            'closed' => false,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        $result = $this->vipService->hasActiveFlashVip($user->idUser);

        $this->assertTrue($result);
    }

    public function test_get_vip_status_for_user_works()
    {
        $user = User::factory()->create();
        vip::factory()->active()->create([
            'idUser' => $user->idUser,
            'dateFNS' => now()->subHours(12),
            'flashDeadline' => 48,
        ]);

        $result = $this->vipService->getVipStatusForUser($user->idUser);

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('vip', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('isActive', $result);
        $this->assertInstanceOf(vip::class, $result['vip']);
    }
}
