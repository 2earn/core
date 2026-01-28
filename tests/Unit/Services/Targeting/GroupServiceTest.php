<?php

namespace Tests\Unit\Services\Targeting;

use App\Models\Group;
use App\Models\Target;
use App\Services\Targeting\GroupService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GroupServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected GroupService $groupService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groupService = new GroupService();
    }

    /**
     * Test getByIdOrFail returns group when found
     */
    public function test_get_by_id_or_fail_returns_group()
    {
        // Arrange
        $group = Group::factory()->create();

        // Act
        $result = $this->groupService->getByIdOrFail($group->id);

        // Assert
        $this->assertInstanceOf(Group::class, $result);
        $this->assertEquals($group->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception for non-existent ID
     */
    public function test_get_by_id_or_fail_throws_exception()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->groupService->getByIdOrFail(99999);
    }

    /**
     * Test getById returns group when found
     */
    public function test_get_by_id_returns_group()
    {
        // Arrange
        $group = Group::factory()->create();

        // Act
        $result = $this->groupService->getById($group->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($group->id, $result->id);
        $this->assertEquals($group->operator, $result->operator);
    }

    /**
     * Test getById returns null for non-existent ID
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->groupService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test create creates new group
     */
    public function test_create_creates_group()
    {
        // Arrange
        $target = Target::factory()->create();
        $data = [
            'operator' => 'AND',
            'target_id' => $target->id,
        ];

        // Act
        $result = $this->groupService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Group::class, $result);
        $this->assertEquals('AND', $result->operator);
        $this->assertEquals($target->id, $result->target_id);
        $this->assertDatabaseHas('groups', [
            'operator' => 'AND',
            'target_id' => $target->id
        ]);
    }

    /**
     * Test create with OR operator
     */
    public function test_create_creates_group_with_or_operator()
    {
        // Arrange
        $target = Target::factory()->create();
        $data = [
            'operator' => 'OR',
            'target_id' => $target->id,
        ];

        // Act
        $result = $this->groupService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('OR', $result->operator);
    }

    /**
     * Test update updates group
     */
    public function test_update_updates_group()
    {
        // Arrange
        $group = Group::factory()->create(['operator' => 'AND']);
        $data = ['operator' => 'OR'];

        // Act
        $result = $this->groupService->update($group->id, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'operator' => 'OR'
        ]);
    }

    /**
     * Test update returns false on failure
     */
    public function test_update_returns_false_on_failure()
    {
        // Arrange
        $invalidData = ['operator' => 'AND'];

        // Act
        $result = $this->groupService->update(99999, $invalidData);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete removes group
     */
    public function test_delete_removes_group()
    {
        // Arrange
        $group = Group::factory()->create();

        // Act
        $result = $this->groupService->delete($group->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    /**
     * Test delete returns false for non-existent group
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->groupService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }


    /**
     * Test group has relationship with target
     */
    public function test_group_has_target_relationship()
    {
        // Arrange
        $target = Target::factory()->create();
        $group = Group::factory()->create(['target_id' => $target->id]);

        // Act
        $result = $this->groupService->getById($group->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($target->id, $result->target_id);
    }

    /**
     * Test update with multiple fields
     */
    public function test_update_with_target_id()
    {
        // Arrange
        $target1 = Target::factory()->create();
        $target2 = Target::factory()->create();
        $group = Group::factory()->create([
            'operator' => 'AND',
            'target_id' => $target1->id
        ]);

        // Act
        $result = $this->groupService->update($group->id, [
            'operator' => 'OR',
            'target_id' => $target2->id
        ]);

        // Assert
        $this->assertTrue($result);

        $group->refresh();
        $this->assertEquals('OR', $group->operator);
        $this->assertEquals($target2->id, $group->target_id);
    }
}
