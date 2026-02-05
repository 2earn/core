<?php

namespace Tests\Unit\Services\Targeting;

use App\Models\Condition;
use App\Models\Group;
use App\Models\Target;
use App\Services\Targeting\ConditionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConditionServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ConditionService $conditionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->conditionService = new ConditionService();
    }

    /**
     * Test getById returns condition when found
     */
    public function test_get_by_id_returns_condition()
    {
        // Arrange
        $condition = Condition::factory()->create();

        // Act
        $result = $this->conditionService->getById($condition->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($condition->id, $result->id);
        $this->assertEquals($condition->operand, $result->operand);
    }

    /**
     * Test getById returns null for non-existent ID
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->conditionService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByIdOrFail returns condition when found
     */
    public function test_get_by_id_or_fail_returns_condition()
    {
        // Arrange
        $condition = Condition::factory()->create();

        // Act
        $result = $this->conditionService->getByIdOrFail($condition->id);

        // Assert
        $this->assertInstanceOf(Condition::class, $result);
        $this->assertEquals($condition->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception for non-existent ID
     */
    public function test_get_by_id_or_fail_throws_exception()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->conditionService->getByIdOrFail(99999);
    }

    /**
     * Test create creates new condition
     */
    public function test_create_creates_condition()
    {
        // Arrange
        $target = Target::factory()->create();
        $data = [
            'operand' => '=',
            'operator' => 'u.id',
            'value' => '123',
            'target_id' => $target->id,
        ];

        // Act
        $result = $this->conditionService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Condition::class, $result);
        $this->assertEquals('=', $result->operand);
        $this->assertEquals('u.id', $result->operator);
        $this->assertEquals('123', $result->value);
        $this->assertDatabaseHas('conditions', [
            'operand' => '=',
            'operator' => 'u.id',
            'value' => '123'
        ]);
    }

    /**
     * Test create with group_id
     */
    public function test_create_creates_condition_with_group()
    {
        // Arrange
        $target = Target::factory()->create();
        $group = Group::factory()->create(['target_id' => $target->id]);
        $data = [
            'operand' => '!=',
            'operator' => 'u.name',
            'value' => 'test',
            'target_id' => $target->id,
            'group_id' => $group->id,
        ];

        // Act
        $result = $this->conditionService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($group->id, $result->group_id);
    }

    /**
     * Test update updates condition
     */
    public function test_update_updates_condition()
    {
        // Arrange
        $condition = Condition::factory()->create(['operand' => '=']);
        $data = ['operand' => '!='];

        // Act
        $result = $this->conditionService->update($condition->id, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('conditions', [
            'id' => $condition->id,
            'operand' => '!='
        ]);
    }

    /**
     * Test update with multiple fields
     */
    public function test_update_with_multiple_fields()
    {
        // Arrange
        $condition = Condition::factory()->create();
        $data = [
            'operand' => '>',
            'operator' => 'u.mobile',
            'value' => '999'
        ];

        // Act
        $result = $this->conditionService->update($condition->id, $data);

        // Assert
        $this->assertTrue($result);

        $condition->refresh();
        $this->assertEquals('>', $condition->operand);
        $this->assertEquals('u.mobile', $condition->operator);
        $this->assertEquals('999', $condition->value);
    }

    /**
     * Test update returns false on failure
     */
    public function test_update_returns_false_on_failure()
    {
        // Act
        $result = $this->conditionService->update(99999, ['operand' => '=']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete removes condition
     */
    public function test_delete_removes_condition()
    {
        // Arrange
        $condition = Condition::factory()->create();

        // Act
        $result = $this->conditionService->delete($condition->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('conditions', ['id' => $condition->id]);
    }

    /**
     * Test delete returns false for non-existent condition
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->conditionService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getOperators returns operators array
     */
    public function test_get_operators_returns_array()
    {
        // Act
        $result = $this->conditionService->getOperators();

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('value', $result[0]);
    }

    /**
     * Test getOperands returns operands array
     */
    public function test_get_operands_returns_array()
    {
        // Act
        $result = $this->conditionService->getOperands();

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertContains('=', $result);
        $this->assertContains('!=', $result);
    }

    /**
     * Test getOperands includes simple operands
     */
    public function test_get_operands_includes_simple_operands()
    {
        // Act
        $result = $this->conditionService->getOperands();

        // Assert
        $this->assertContains('=', $result);
        $this->assertContains('!=', $result);
        $this->assertContains('<', $result);
        $this->assertContains('>', $result);
        $this->assertContains('<=', $result);
        $this->assertContains('>=', $result);
    }

    /**
     * Test getOperands includes complex operands
     */
    public function test_get_operands_includes_complex_operands()
    {
        // Act
        $result = $this->conditionService->getOperands();

        // Assert
        $this->assertContains('END WITH', $result);
        $this->assertContains('START WITH', $result);
        $this->assertContains('CONTAIN', $result);
    }


    /**
     * Test condition has relationship with target
     */
    public function test_condition_has_target_relationship()
    {
        // Arrange
        $target = Target::factory()->create();
        $condition = Condition::factory()->create(['target_id' => $target->id]);

        // Act
        $result = $this->conditionService->getById($condition->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($target->id, $result->target_id);
    }

    /**
     * Test condition has relationship with group
     */
    public function test_condition_has_group_relationship()
    {
        // Arrange
        $target = Target::factory()->create();
        $group = Group::factory()->create(['target_id' => $target->id]);
        $condition = Condition::factory()->create([
            'target_id' => $target->id,
            'group_id' => $group->id
        ]);

        // Act
        $result = $this->conditionService->getById($condition->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($group->id, $result->group_id);

    }

    /**
     * Test getOperators method returns array of operators
     */
    public function test_get_operators_works()
    {
        // Act
        $result = $this->conditionService->getOperators();

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Verify structure
        foreach ($result as $operator) {
            $this->assertIsArray($operator);
            $this->assertArrayHasKey('name', $operator);
            $this->assertArrayHasKey('value', $operator);
        }

        // Verify some expected operators exist
        $operatorValues = array_column($result, 'value');
        $this->assertContains('u.id', $operatorValues);
        $this->assertContains('u.name', $operatorValues);
        $this->assertContains('u.email', $operatorValues);
    }

    /**
     * Test getOperands method returns array of operands
     */
    public function test_get_operands_works()
    {
        // Act
        $result = $this->conditionService->getOperands();

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // Verify simple operands are present
        $this->assertContains('=', $result);
        $this->assertContains('!=', $result);
        $this->assertContains('<', $result);
        $this->assertContains('>', $result);
        $this->assertContains('<=', $result);
        $this->assertContains('>=', $result);

        // Verify complex operands are present
        $this->assertContains('END WITH', $result);
        $this->assertContains('START WITH', $result);
        $this->assertContains('CONTAIN', $result);

        // Verify total count (6 simple + 3 complex = 9)
        $this->assertCount(9, $result);
    }
}
