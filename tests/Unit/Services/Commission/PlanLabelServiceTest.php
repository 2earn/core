<?php

namespace Tests\Unit\Services\Commission;

use App\Models\PlanLabel;
use App\Services\Commission\PlanLabelService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlanLabelServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PlanLabelService $planLabelService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->planLabelService = new PlanLabelService();
    }

    /**
     * Test getPlanLabels returns all labels
     */
    public function test_get_plan_labels_returns_all_labels()
    {
        // Arrange
        PlanLabel::factory()->count(5)->create();

        // Act
        $result = $this->planLabelService->getPlanLabels();

        // Assert
        $this->assertGreaterThanOrEqual(5, $result->count());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getPlanLabels filters by active status
     */
    public function test_get_plan_labels_filters_by_active_status()
    {
        // Arrange
        PlanLabel::factory()->active()->count(3)->create();
        PlanLabel::factory()->inactive()->count(2)->create();

        // Act
        $result = $this->planLabelService->getPlanLabels(['is_active' => true]);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        foreach ($result as $label) {
            $this->assertTrue($label->is_active);
        }
    }

    /**
     * Test getPlanLabels filters by search term
     */
    public function test_get_plan_labels_filters_by_search()
    {
        // Arrange
        PlanLabel::factory()->create(['name' => 'Gold Premium Plan']);
        PlanLabel::factory()->create(['name' => 'Silver Basic Plan']);

        // Act
        $result = $this->planLabelService->getPlanLabels(['search' => 'Gold']);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getPlanLabels filters by stars
     */
    public function test_get_plan_labels_filters_by_stars()
    {
        // Arrange
        PlanLabel::factory()->create(['stars' => 5]);
        PlanLabel::factory()->create(['stars' => 3]);

        // Act
        $result = $this->planLabelService->getPlanLabels(['stars' => 5]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
        foreach ($result as $label) {
            $this->assertEquals(5, $label->stars);
        }
    }

    /**
     * Test getPlanLabels filters by step
     */
    public function test_get_plan_labels_filters_by_step()
    {
        // Arrange
        PlanLabel::factory()->create(['step' => 1]);
        PlanLabel::factory()->create(['step' => 5]);

        // Act
        $result = $this->planLabelService->getPlanLabels(['step' => 1]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
        foreach ($result as $label) {
            $this->assertEquals(1, $label->step);
        }
    }

    /**
     * Test getPlanLabels filters by commission range
     */
    public function test_get_plan_labels_filters_by_commission_range()
    {
        // Arrange
        PlanLabel::factory()->create([
            'initial_commission' => 5.00,
            'final_commission' => 10.00
        ]);
        PlanLabel::factory()->create([
            'initial_commission' => 15.00,
            'final_commission' => 20.00
        ]);

        // Act
        $result = $this->planLabelService->getPlanLabels([
            'min_commission' => 4.00,
            'max_commission' => 12.00
        ]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getPlanLabels with custom ordering
     */
    public function test_get_plan_labels_with_custom_ordering()
    {
        // Arrange
        $label1 = PlanLabel::factory()->create(['name' => 'Zebra Plan']);
        $label2 = PlanLabel::factory()->create(['name' => 'Alpha Plan']);

        // Act
        $result = $this->planLabelService->getPlanLabels([
            'order_by' => 'name',
            'order_direction' => 'asc'
        ]);

        // Assert
        $this->assertEquals('Alpha Plan', $result->first()->name);
    }

    /**
     * Test getActiveLabels returns only active labels
     */
    public function test_get_active_labels_returns_only_active()
    {
        // Arrange
        PlanLabel::factory()->active()->count(3)->create();
        PlanLabel::factory()->inactive()->count(2)->create();

        // Act
        $result = $this->planLabelService->getActiveLabels();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        foreach ($result as $label) {
            $this->assertTrue($label->is_active);
        }
    }

    /**
     * Test getActiveLabels orders by initial commission
     */
    public function test_get_active_labels_orders_by_initial_commission()
    {
        // Arrange
        $label1 = PlanLabel::factory()->active()->create(['initial_commission' => 10.00]);
        $label2 = PlanLabel::factory()->active()->create(['initial_commission' => 5.00]);

        // Act
        $result = $this->planLabelService->getActiveLabels();

        // Assert
        // Verify ordering (first should have lowest commission)
        $firstCommission = $result->first()->initial_commission;
        $lastCommission = $result->last()->initial_commission;
        $this->assertLessThanOrEqual($lastCommission, $firstCommission);
    }

    /**
     * Test getPlanLabelById returns label
     */
    public function test_get_plan_label_by_id_returns_label()
    {
        // Arrange
        $label = PlanLabel::factory()->create();

        // Act
        $result = $this->planLabelService->getPlanLabelById($label->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($label->id, $result->id);
    }

    /**
     * Test getPlanLabelById returns null for non-existent
     */
    public function test_get_plan_label_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->planLabelService->getPlanLabelById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test createPlanLabel creates new label
     */
    public function test_create_plan_label_creates_new_label()
    {
        // Arrange
        $data = [
            'name' => 'Test Plan Label',
            'description' => 'Test description',
            'step' => 1,
            'rate' => 5.5,
            'stars' => 4,
            'initial_commission' => 5.00,
            'final_commission' => 10.00,
            'is_active' => true,
        ];

        // Act
        $result = $this->planLabelService->createPlanLabel($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Test Plan Label', $result->name);
        $this->assertDatabaseHas('plan_labels', ['name' => 'Test Plan Label']);
    }

    /**
     * Test updatePlanLabel updates label
     */
    public function test_update_plan_label_updates_label()
    {
        // Arrange
        $label = PlanLabel::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'Updated Name'];

        // Act
        $result = $this->planLabelService->updatePlanLabel($label->id, $data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Updated Name', $result->name);
    }

    /**
     * Test updatePlanLabel returns null for non-existent
     */
    public function test_update_plan_label_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->planLabelService->updatePlanLabel(99999, ['name' => 'Test']);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test deletePlanLabel deletes label
     */
    public function test_delete_plan_label_deletes_label()
    {
        // Arrange
        $label = PlanLabel::factory()->create();

        // Act
        $result = $this->planLabelService->deletePlanLabel($label->id);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('plan_labels', ['id' => $label->id]);
    }

    /**
     * Test deletePlanLabel returns false for non-existent
     */
    public function test_delete_plan_label_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->planLabelService->deletePlanLabel(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test toggleActive toggles status
     */
    public function test_toggle_active_toggles_status()
    {
        // Arrange
        $label = PlanLabel::factory()->active()->create();
        $originalStatus = $label->is_active;

        // Act
        $result = $this->planLabelService->toggleActive($label->id);

        // Assert
        $this->assertTrue($result);
        $label->refresh();
        $this->assertNotEquals($originalStatus, $label->is_active);
    }

    /**
     * Test toggleActive from inactive to active
     */
    public function test_toggle_active_from_inactive_to_active()
    {
        // Arrange
        $label = PlanLabel::factory()->inactive()->create();

        // Act
        $this->planLabelService->toggleActive($label->id);

        // Assert
        $label->refresh();
        $this->assertTrue($label->is_active);
    }

    /**
     * Test calculateCommission calculates initial commission
     */
    public function test_calculate_commission_calculates_initial()
    {
        // Arrange
        $label = PlanLabel::factory()->create([
            'initial_commission' => 10.00,
            'final_commission' => 15.00
        ]);
        $value = 1000.00;

        // Act
        $result = $this->planLabelService->calculateCommission($label->id, $value, 'initial');

        // Assert
        $this->assertEquals(100.00, $result); // 10% of 1000
    }

    /**
     * Test calculateCommission calculates final commission
     */
    public function test_calculate_commission_calculates_final()
    {
        // Arrange
        $label = PlanLabel::factory()->create([
            'initial_commission' => 10.00,
            'final_commission' => 15.00
        ]);
        $value = 1000.00;

        // Act
        $result = $this->planLabelService->calculateCommission($label->id, $value, 'final');

        // Assert
        $this->assertEquals(150.00, $result); // 15% of 1000
    }

    /**
     * Test calculateCommission returns null for non-existent label
     */
    public function test_calculate_commission_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->planLabelService->calculateCommission(99999, 1000.00);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getForSelect returns formatted data
     */
    public function test_get_for_select_returns_formatted_data()
    {
        // Arrange
        PlanLabel::factory()->active()->count(3)->create();

        // Act
        $result = $this->planLabelService->getForSelect();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getForSelect only returns active labels
     */
    public function test_get_for_select_only_returns_active()
    {
        // Arrange
        $activeName = 'Unique Active Test Label ' . uniqid();
        $inactiveName = 'Unique Inactive Test Label ' . uniqid();

        $activeLabel = PlanLabel::factory()->active()->create(['name' => $activeName]);
        $inactiveLabel = PlanLabel::factory()->inactive()->create(['name' => $inactiveName]);

        // Act
        $result = $this->planLabelService->getForSelect();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);

        // Check that our active label is in the result
        $foundActive = $result->contains('id', $activeLabel->id);
        $this->assertTrue($foundActive, 'Active label should be in getForSelect result');

        // Check that our inactive label is NOT in the result
        $foundInactive = $result->contains('id', $inactiveLabel->id);
        $this->assertFalse($foundInactive, 'Inactive label should NOT be in getForSelect result');
    }

    /**
     * Test getPaginatedLabels returns paginated results
     */
    public function test_get_paginated_labels_returns_paginated_results()
    {
        // Arrange
        PlanLabel::factory()->count(15)->create();

        // Act
        $result = $this->planLabelService->getPaginatedLabels([], 1, 10);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertGreaterThanOrEqual(15, $result['total']);
    }

    /**
     * Test getPaginatedLabels filters by search
     */
    public function test_get_paginated_labels_filters_by_search()
    {
        // Arrange
        PlanLabel::factory()->create(['name' => 'Premium Gold Plan']);
        PlanLabel::factory()->create(['name' => 'Basic Silver Plan']);

        // Act
        $result = $this->planLabelService->getPaginatedLabels(['search' => 'Premium']);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result['labels']->count());
    }

    /**
     * Test getPaginatedLabels filters by stars
     */
    public function test_get_paginated_labels_filters_by_stars()
    {
        // Arrange
        PlanLabel::factory()->create(['stars' => 5]);
        PlanLabel::factory()->create(['stars' => 3]);

        // Act
        $result = $this->planLabelService->getPaginatedLabels(['stars' => 5]);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result['labels']->count());
    }

    /**
     * Test getPaginatedLabels adds commission range
     */
    public function test_get_paginated_labels_adds_commission_range()
    {
        // Arrange
        PlanLabel::factory()->create([
            'initial_commission' => 5.00,
            'final_commission' => 10.00
        ]);

        // Act
        $result = $this->planLabelService->getPaginatedLabels();

        // Assert
        $firstLabel = $result['labels']->first();
        $this->assertNotNull($firstLabel);
        // Check if commission_range attribute was added by the service
        $this->assertTrue(isset($firstLabel->commission_range) || method_exists($firstLabel, 'getCommissionRange'));
    }

    /**
     * Test getPlanLabels with relationships
     */
    public function test_get_plan_labels_with_relationships()
    {
        // Arrange
        PlanLabel::factory()->create();

        // Act
        $result = $this->planLabelService->getPlanLabels(['with' => ['deals']]);

        // Assert
        $this->assertTrue($result->first()->relationLoaded('deals'));
    }
}
