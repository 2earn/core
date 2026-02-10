<?php

namespace Tests\Feature\Api\v2;

use App\Models\PartnerPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for PartnerPaymentController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\PartnerPaymentController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('partner_payments')]
class PartnerPaymentControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_get_filtered_payments()
    {
        PartnerPayment::factory()->count(10)->create();

        $response = $this->getJson('/api/v2/partner-payments?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_filter_by_status()
    {
        PartnerPayment::factory()->count(5)->pending()->create();

        $response = $this->getJson('/api/v2/partner-payments?status_filter=pending');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_by_date_range()
    {
        $response = $this->getJson('/api/v2/partner-payments?from_date=2024-01-01&to_date=2024-12-31');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_search_payments()
    {
        $response = $this->getJson('/api/v2/partner-payments?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_payment_by_id()
    {
        $payment = PartnerPayment::factory()->create();

        $response = $this->getJson("/api/v2/partner-payments/{$payment->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_payment()
    {
        $response = $this->getJson('/api/v2/partner-payments/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_get_payments_by_partner_id()
    {
        $partner = User::factory()->create();
        PartnerPayment::factory()->count(3)->create(['partner_id' => $partner->id]);

        $response = $this->getJson("/api/v2/partner-payments/partner/{$partner->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_payment()
    {
        $partner = User::factory()->create();

        $data = [
            'partner_id' => $partner->id,
            'amount' => 1000,
            'method' => 'bank_transfer',
            'status' => 'pending'
        ];

        $response = $this->postJson('/api/v2/partner-payments', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_payment_creation()
    {
        $response = $this->postJson('/api/v2/partner-payments', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['partner_id', 'amount']);
    }

    #[Test]
    public function it_can_update_payment()
    {
        $payment = PartnerPayment::factory()->create();

        $data = [
            'amount' => 2000,
            'status' => 'validated'
        ];

        $response = $this->putJson("/api/v2/partner-payments/{$payment->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_validate_payment()
    {
        $payment = PartnerPayment::factory()->create(['status' => 'pending']);

        $response = $this->postJson("/api/v2/partner-payments/{$payment->id}/validate");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_reject_payment()
    {
        $payment = PartnerPayment::factory()->pending()->create();

        $response = $this->postJson("/api/v2/partner-payments/{$payment->id}/reject", [
            'reason' => 'Invalid data'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/partner-payments?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }

    #[Test]
    public function it_validates_status_filter()
    {
        $response = $this->getJson('/api/v2/partner-payments?status_filter=invalid');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status_filter']);
    }
}

