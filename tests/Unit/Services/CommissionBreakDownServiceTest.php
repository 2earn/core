<?php

namespace Tests\Unit\Services;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use App\Services\CommissionBreakDownService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommissionBreakDownServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CommissionBreakDownService $commissionBreakDownService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commissionBreakDownService = new CommissionBreakDownService();
    }

    /**
     * Test getByDealId returns commission breakdowns for deal
     */
    public function test_get_by_deal_id_returns_breakdowns()
    {
        // Arrange
        $deal = Deal::factory()->create();
        CommissionBreakDown::factory()->count(3)->create(['deal_id' => $deal->id]);
        CommissionBreakDown::factory()->count(2)->create(); // Other deals

        // Act
        $result = $this->commissionBreakDownService->getByDealId($deal->id);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());

        foreach ($result as $breakdown) {
            $this->assertEquals($deal->id, $breakdown->deal_id);
        }
    }

    /**
     * Test getByDealId orders results correctly
     */
    public function test_get_by_deal_id_orders_results()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $breakdown1 = CommissionBreakDown::factory()->create(['deal_id' => $deal->id]);
        $breakdown2 = CommissionBreakDown::factory()->create(['deal_id' => $deal->id]);

        // Act
        $result = $this->commissionBreakDownService->getByDealId($deal->id, 'id', 'ASC');

        // Assert
        $this->assertEquals($breakdown1->id, $result->first()->id);
    }

    /**
     * Test getById returns commission breakdown
     */
    public function test_get_by_id_returns_breakdown()
    {
        // Arrange
        $breakdown = CommissionBreakDown::factory()->create();

        // Act
        $result = $this->commissionBreakDownService->getById($breakdown->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($breakdown->id, $result->id);
    }

    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->commissionBreakDownService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test calculateTotals calculates all totals correctly
     */
    public function test_calculate_totals_calculates_correctly()
    {
        // Arrange
        $deal = Deal::factory()->create();
        CommissionBreakDown::factory()->create([
            'deal_id' => $deal->id,
            'cash_jackpot' => 100,
            'cash_company_profit' => 50,
            'cash_cashback' => 25,
            'cash_tree' => 10
        ]);
        CommissionBreakDown::factory()->create([
            'deal_id' => $deal->id,
            'cash_jackpot' => 200,
            'cash_company_profit' => 100,
            'cash_cashback' => 50,
            'cash_tree' => 20
        ]);

        // Act
        $result = $this->commissionBreakDownService->calculateTotals($deal->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('jackpot', $result);
        $this->assertArrayHasKey('earn_profit', $result);
        $this->assertArrayHasKey('proactive_cashback', $result);
        $this->assertArrayHasKey('tree_remuneration', $result);

        $this->assertEquals(300, $result['jackpot']);
        $this->assertEquals(150, $result['earn_profit']);
        $this->assertEquals(75, $result['proactive_cashback']);
        $this->assertEquals(30, $result['tree_remuneration']);
    }

    /**
     * Test calculateTotals returns zeros for non-existent deal
     */
    public function test_calculate_totals_returns_zeros_for_nonexistent()
    {
        // Act
        $result = $this->commissionBreakDownService->calculateTotals(99999);

        // Assert
        $this->assertEquals(0, $result['jackpot']);
        $this->assertEquals(0, $result['earn_profit']);
        $this->assertEquals(0, $result['proactive_cashback']);
        $this->assertEquals(0, $result['tree_remuneration']);
    }

    /**
     * Test create creates commission breakdown
     */
    public function test_create_creates_breakdown()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $data = [
            'deal_id' => $deal->id,
            'cash_jackpot' => 100,
            'cash_company_profit' => 50,
            'cash_cashback' => 25,
            'cash_tree' => 10
        ];

        // Act
        $result = $this->commissionBreakDownService->create($data);

        // Assert
        $this->assertInstanceOf(CommissionBreakDown::class, $result);
        $this->assertEquals($deal->id, $result->deal_id);
        $this->assertEquals(100, $result->cash_jackpot);
        $this->assertDatabaseHas('commission_break_downs', ['deal_id' => $deal->id]);
    }

    /**
     * Test update updates commission breakdown
     */
    public function test_update_updates_breakdown()
    {
        // Arrange
        $breakdown = CommissionBreakDown::factory()->create(['cash_jackpot' => 100]);
        $data = ['cash_jackpot' => 200];

        // Act
        $result = $this->commissionBreakDownService->update($breakdown->id, $data);

        // Assert
        $this->assertTrue($result);

        $breakdown->refresh();
        $this->assertEquals(200, $breakdown->cash_jackpot);
    }

    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->commissionBreakDownService->update(99999, ['cash_jackpot' => 100]);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete deletes commission breakdown
     */
    public function test_delete_deletes_breakdown()
    {
        // Arrange
        $breakdown = CommissionBreakDown::factory()->create();

        // Act
        $result = $this->commissionBreakDownService->delete($breakdown->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('commission_break_downs', ['id' => $breakdown->id]);
    }

    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->commissionBreakDownService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getByDealId with DESC ordering
     */
    public function test_get_by_deal_id_orders_desc()
    {
        // Arrange
        $deal = Deal::factory()->create();
        $breakdown1 = CommissionBreakDown::factory()->create(['deal_id' => $deal->id]);
        $breakdown2 = CommissionBreakDown::factory()->create(['deal_id' => $deal->id]);

        // Act
        $result = $this->commissionBreakDownService->getByDealId($deal->id, 'id', 'DESC');

        // Assert
        $this->assertEquals($breakdown2->id, $result->first()->id);
    }
}

        $this->markTestIncomplete('Test for update not yet implemented');
    }

    /**
     * Test delete method
     * TODO: Implement actual test logic
     */
    public function test_delete_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->delete();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for delete not yet implemented');
    }
}
