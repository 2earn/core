<?php

namespace Tests\Feature\Api;

use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('api')]
class BalanceOperationApiTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function it_can_get_filtered_operations()
    {
        // Arrange
        BalanceOperation::factory()->count(5)->create([
            'operation' => 'Transfer'
        ]);

        // Act
        $response = $this->getJson('/api/v1/balance/operations/filtered?search=Transfer&per_page=10');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'per_page',
                'total'
            ]);
    }

    /** @test */
    public function it_can_get_all_operations()
    {
        // Arrange - Count initial operations
        $initialCount = BalanceOperation::count();

        // Create 3 new operations
        BalanceOperation::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/balance/operations/all');

        // Assert
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertGreaterThanOrEqual($initialCount + 3, count($data),
            "Expected at least " . ($initialCount + 3) . " operations, got " . count($data));
    }

    /** @test */
    public function it_can_get_operation_by_id()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Test Operation'
        ]);

        // Act
        $response = $this->getJson("/api/v1/balance/operations/{$operation->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'id' => $operation->id,
                'operation' => 'Test Operation'
            ]);
    }

    /** @test */
    public function it_returns_404_when_operation_not_found()
    {
        // Act
        $response = $this->getJson('/api/v1/balance/operations/999999');

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Operation not found'
            ]);
    }

    /** @test */
    public function it_can_create_operation()
    {
        // Arrange
        $data = [
            'ref' => 'REF-' . uniqid(),
            'operation' => 'New Transfer',
            'direction' => 'IN',
            'balance_id' => 1,
            'operation_category_id' => 1,
        ];

        // Act
        $response = $this->postJson('/api/v1/balance/operations', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'operation' => 'New Transfer',
                'direction' => 'IN',
            ]);

        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'New Transfer'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating()
    {
        // Act
        $response = $this->postJson('/api/v1/balance/operations', []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['operation']);
    }

    /** @test */
    public function it_can_update_operation()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Original Operation',
            'direction' => 'IN'
        ]);

        $updateData = [
            'operation' => 'Updated Operation',
            'direction' => 'OUT'
        ];

        // Act
        $response = $this->putJson("/api/v1/balance/operations/{$operation->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Operation updated successfully'
            ]);

        $this->assertDatabaseHas('balance_operations', [
            'id' => $operation->id,
            'operation' => 'Updated Operation',
            'direction' => 'OUT'
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_non_existent_operation()
    {
        // Act
        $response = $this->putJson('/api/v1/balance/operations/999999', [
            'operation' => 'Updated'
        ]);

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Operation not found'
            ]);
    }

    /** @test */
    public function it_can_delete_operation()
    {
        // Arrange
        $operation = BalanceOperation::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/balance/operations/{$operation->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Operation deleted successfully'
            ]);

        $this->assertDatabaseMissing('balance_operations', [
            'id' => $operation->id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_non_existent_operation()
    {
        // Act
        $response = $this->deleteJson('/api/v1/balance/operations/999999');

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Operation not found'
            ]);
    }

    /** @test */
    public function it_can_get_category_name()
    {
        // Arrange
        $uniqueName = 'Transfer Category ' . uniqid();
        $category = OperationCategory::factory()->create([
            'name' => $uniqueName
        ]);

        // Act
        $response = $this->getJson("/api/v1/balance/operations/category/{$category->id}/name");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'category_name' => $uniqueName
            ]);
    }

    /** @test */
    public function it_returns_dash_for_non_existent_category()
    {
        // Act
        $response = $this->getJson('/api/v1/balance/operations/category/999999/name');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'category_name' => '-'
            ]);
    }


    /** @test */
    public function it_validates_parent_operation_id_exists()
    {
        // Act
        $response = $this->postJson('/api/v1/balance/operations', [
            'operation' => 'Test',
            'parent_operation_id' => 999999 // Non-existent parent
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['parent_operation_id']);
    }

    /** @test */
    public function it_validates_operation_category_id_exists()
    {
        // Act
        $response = $this->postJson('/api/v1/balance/operations', [
            'operation' => 'Test',
            'operation_category_id' => 999999 // Non-existent category
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['operation_category_id']);
    }

    /** @test */
    public function it_includes_relationships_in_response()
    {
        // Arrange - Get max IDs to avoid auto-increment issues
        $maxCategoryId = \DB::table('operation_categories')->max('id') ?? 0;
        $maxOperationId = \DB::table('balance_operations')->max('id') ?? 0;

        $categoryId = $maxCategoryId + 1;
        $parentOperationId = $maxOperationId + 1;
        $operationId = $maxOperationId + 2;

        // Create category with explicit ID
        \DB::table('operation_categories')->insert([
            'id' => $categoryId,
            'name' => 'Test Category ' . uniqid(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create parent operation with explicit ID
        \DB::table('balance_operations')->insert([
            'id' => $parentOperationId,
            'ref' => 'REF-PARENT-' . uniqid(),
            'operation' => 'Parent Operation',
            'direction' => 'IN',
            'balance_id' => 1,
            'operation_category_id' => $categoryId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create operation with relationships
        \DB::table('balance_operations')->insert([
            'id' => $operationId,
            'ref' => 'REF-CHILD-' . uniqid(),
            'operation' => 'Child Operation',
            'direction' => 'OUT',
            'balance_id' => 1,
            'parent_operation_id' => $parentOperationId,
            'operation_category_id' => $categoryId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Act
        $response = $this->getJson("/api/v1/balance/operations/{$operationId}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'operation',
            ]);

        // Verify relationships are loaded
        $data = $response->json();

        // Check that parent and category keys exist
        $this->assertArrayHasKey('parent', $data, 'Response should include parent key');
        $this->assertArrayHasKey('opeartionCategory', $data, 'Response should include opeartionCategory key');

        // Verify relationships are not null and have the correct IDs
        $this->assertNotNull($data['parent'], 'Parent relationship should be loaded');
        $this->assertNotNull($data['opeartionCategory'], 'Operation category relationship should be loaded');
        $this->assertEquals($parentOperationId, $data['parent']['id'], 'Parent ID should match');
        $this->assertEquals($categoryId, $data['opeartionCategory']['id'], 'Category ID should match');
    }

    /** @test */
    public function it_can_search_by_multiple_fields()
    {
        // Arrange - Use unique identifier to avoid conflicts
        $uniqueId = 'UNIQUE' . time();
        BalanceOperation::factory()->create(['operation' => "Transfer {$uniqueId}"]);
        BalanceOperation::factory()->create(['operation' => 'Withdrawal XYZ']);

        // Act - Search for unique identifier
        $response = $this->getJson("/api/v1/balance/operations/filtered?search={$uniqueId}");

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(1, count($data));

        // Verify at least one result contains our unique ID
        $found = false;
        foreach ($data as $item) {
            if (str_contains($item['operation'], $uniqueId)) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Expected to find operation with {$uniqueId}");
    }
}

