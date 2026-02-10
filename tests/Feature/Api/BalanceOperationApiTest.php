<?php

namespace Tests\Feature\Api;

use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BalanceOperationApiTest extends TestCase
{
    use WithFaker;

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
        // Arrange
        BalanceOperation::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/balance/operations/all');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3);
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
            'operation' => 'New Transfer',
            'io' => 'I',
            'source' => 'system',
            'note' => 'Test note'
        ];

        // Act
        $response = $this->postJson('/api/v1/balance/operations', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'operation' => 'New Transfer',
                'io' => 'I',
                'source' => 'system',
                'note' => 'Test note'
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
            'note' => 'Original note'
        ]);

        $updateData = [
            'operation' => 'Updated Operation',
            'note' => 'Updated note'
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
            'note' => 'Updated note'
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
        $category = OperationCategory::factory()->create([
            'name' => 'Transfer Category'
        ]);

        // Act
        $response = $this->getJson("/api/v1/balance/operations/category/{$category->id}/name");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'category_name' => 'Transfer Category'
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
    public function it_requires_authentication()
    {
        // Arrange
        Sanctum::actingAs($this->user, [], false); // Revoke authentication

        // Act
        $response = $this->getJson('/api/v1/balance/operations/all');

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_parent_id_exists()
    {
        // Act
        $response = $this->postJson('/api/v1/balance/operations', [
            'operation' => 'Test',
            'parent_id' => 999999 // Non-existent parent
        ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['parent_id']);
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
        // Arrange
        $category = OperationCategory::factory()->create();
        $parentOperation = BalanceOperation::factory()->create();
        $operation = BalanceOperation::factory()->create([
            'parent_id' => $parentOperation->id,
            'operation_category_id' => $category->id
        ]);

        // Act
        $response = $this->getJson("/api/v1/balance/operations/{$operation->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'parent' => ['id', 'operation'],
                'opeartionCategory' => ['id', 'name']
            ]);
    }

    /** @test */
    public function it_can_search_by_multiple_fields()
    {
        // Arrange
        BalanceOperation::factory()->create(['operation' => 'Transfer ABC']);
        BalanceOperation::factory()->create(['balance_id' => 12345]);
        BalanceOperation::factory()->create(['operation' => 'Withdrawal XYZ']);

        // Act - Search for 'ABC'
        $response = $this->getJson('/api/v1/balance/operations/filtered?search=ABC');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Transfer ABC', $data[0]['operation']);
    }
}

