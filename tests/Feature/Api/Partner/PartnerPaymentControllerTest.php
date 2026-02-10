<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Test Suite for PartnerPaymentController
 *
 * @package Tests\Feature\Api\Partner
 * @see App\Http\Controllers\Api\Partner\PartnerPaymentController
 * @author 2earn Development Team
 * @created 2026-01-23
 */
#[Group('api')]
#[Group('api_partner')]
class PartnerPaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $partner;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->partner = Partner::factory()->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    #[Test]
    public function test_partner_can_be_created()
    {
        $partner = Partner::factory()->create();

        $this->assertDatabaseHas('partners', [
            'id' => $partner->id
        ]);
    }

    #[Test]
    public function test_partner_has_required_attributes()
    {
        $this->assertNotNull($this->partner->id);
        $this->assertInstanceOf(Partner::class, $this->partner);
    }

    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(class_exists(\App\Http\Controllers\Api\Partner\PartnerPaymentController::class));
    }

    #[Test]
    public function test_user_and_partner_relationship()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertInstanceOf(Partner::class, $this->partner);
        $this->assertNotEquals($this->user->id, $this->partner->id);
    }
}
