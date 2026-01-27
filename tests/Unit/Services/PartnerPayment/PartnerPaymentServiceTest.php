<?php

namespace Tests\Unit\Services\PartnerPayment;

use App\Models\PartnerPayment;
use App\Models\User;
use App\Services\PartnerPayment\PartnerPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PartnerPaymentService $partnerPaymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partnerPaymentService = new PartnerPaymentService();
    }

    /**
     * Test create method
     */
    public function test_create_works()
    {
        // Arrange
        $partner = User::factory()->create();
        $data = [
            'amount' => 1500.50,
            'method' => 'bank_transfer',
            'payment_date' => now(),
            'partner_id' => $partner->id,
        ];

        // Act
        $result = $this->partnerPaymentService->create($data);

        // Assert
        $this->assertInstanceOf(PartnerPayment::class, $result);
        $this->assertEquals(1500.50, $result->amount);
        $this->assertEquals($partner->id, $result->partner_id);
        $this->assertDatabaseHas('partner_payments', [
            'amount' => 1500.50,
            'partner_id' => $partner->id,
        ]);
    }

    /**
     * Test update method
     */
    public function test_update_works()
    {
        // Arrange
        $payment = PartnerPayment::factory()->create(['amount' => 1000]);
        $updateData = ['amount' => 1500];

        // Act
        $result = $this->partnerPaymentService->update($payment->id, $updateData);

        // Assert
        $this->assertEquals(1500, $result->amount);
        $this->assertDatabaseHas('partner_payments', [
            'id' => $payment->id,
            'amount' => 1500,
        ]);
    }

    /**
     * Test validatePayment method
     */
    public function test_validate_payment_works()
    {
        // Arrange
        $payment = PartnerPayment::factory()->pending()->create();
        $validator = User::factory()->create();

        // Act
        $result = $this->partnerPaymentService->validatePayment($payment->id, $validator->id);

        // Assert
        $this->assertTrue($result->isValidated());
        $this->assertEquals($validator->id, $result->validated_by);
        $this->assertNotNull($result->validated_at);
    }

    /**
     * Test rejectPayment method
     */
    public function test_reject_payment_works()
    {
        // Arrange
        $payment = PartnerPayment::factory()->pending()->create();
        $rejector = User::factory()->create();
        $reason = 'Invalid documentation';

        // Act
        $result = $this->partnerPaymentService->rejectPayment($payment->id, $rejector->id, $reason);

        // Assert
        $this->assertTrue($result->isRejected());
        $this->assertEquals($rejector->id, $result->rejected_by);
        $this->assertEquals($reason, $result->rejection_reason);
        $this->assertNotNull($result->rejected_at);
    }

    /**
     * Test getByPartnerId method
     */
    public function test_get_by_partner_id_works()
    {
        // Arrange
        $partner = User::factory()->create();
        PartnerPayment::factory()->count(5)->create(['partner_id' => $partner->id]);
        PartnerPayment::factory()->count(3)->create();

        // Act
        $result = $this->partnerPaymentService->getByPartnerId($partner->id);

        // Assert
        $this->assertCount(5, $result);
        $this->assertTrue($result->every(fn($p) => $p->partner_id === $partner->id));
    }

    /**
     * Test getById method
     */
    public function test_get_by_id_works()
    {
        // Arrange
        $payment = PartnerPayment::factory()->create();

        // Act
        $result = $this->partnerPaymentService->getById($payment->id);

        // Assert
        $this->assertInstanceOf(PartnerPayment::class, $result);
        $this->assertEquals($payment->id, $result->id);
    }

    /**
     * Test getPayments method
     */
    public function test_get_payments_works()
    {
        // Arrange
        PartnerPayment::factory()->count(10)->create();

        // Act
        $result = $this->partnerPaymentService->getPayments([], 5);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    }

    /**
     * Test delete method
     */
    public function test_delete_works()
    {
        // Arrange
        $payment = PartnerPayment::factory()->pending()->create();

        // Act
        $result = $this->partnerPaymentService->delete($payment->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('partner_payments', ['id' => $payment->id]);
    }

    /**
     * Test getTotalPaymentsByPartner method
     */
    public function test_get_total_payments_by_partner_works()
    {
        // Arrange
        $partner = User::factory()->create();
        PartnerPayment::factory()->validated()->count(3)->create([
            'partner_id' => $partner->id,
            'amount' => 1000,
        ]);

        // Act
        $result = $this->partnerPaymentService->getTotalPaymentsByPartner($partner->id, true);

        // Assert
        $this->assertEquals(3000, $result);
    }

    /**
     * Test getPendingPayments method
     */
    public function test_get_pending_payments_works()
    {
        // Arrange
        PartnerPayment::factory()->pending()->count(5)->create();
        PartnerPayment::factory()->validated()->count(3)->create();

        // Act
        $result = $this->partnerPaymentService->getPendingPayments();

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getValidatedPayments method
     */
    public function test_get_validated_payments_works()
    {
        // Arrange
        PartnerPayment::factory()->validated()->count(4)->create();
        PartnerPayment::factory()->pending()->count(2)->create();

        // Act
        $result = $this->partnerPaymentService->getValidatedPayments();

        // Assert
        $this->assertCount(4, $result);
    }

    /**
     * Test getStats method
     */
    public function test_get_stats_works()
    {
        // Arrange
        PartnerPayment::factory()->validated()->count(3)->create(['amount' => 1000]);
        PartnerPayment::factory()->pending()->count(2)->create(['amount' => 500]);
        PartnerPayment::factory()->rejected()->count(1)->create(['amount' => 300]);

        // Act
        $result = $this->partnerPaymentService->getStats();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_payments', $result);
        $this->assertArrayHasKey('pending_payments', $result);
        $this->assertArrayHasKey('validated_payments', $result);
        $this->assertArrayHasKey('rejected_payments', $result);
        $this->assertArrayHasKey('total_amount', $result);
        $this->assertEquals(6, $result['total_payments']);
    }

    /**
     * Test getPaymentMethods method
     */
    public function test_get_payment_methods_works()
    {
        // Arrange
        PartnerPayment::factory()->create(['method' => 'bank_transfer']);
        PartnerPayment::factory()->create(['method' => 'paypal']);

        // Act
        $result = $this->partnerPaymentService->getPaymentMethods();

        // Assert
        $this->assertGreaterThanOrEqual(2, $result->count());
    }
}
