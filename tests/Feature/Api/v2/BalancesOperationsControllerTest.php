<?php

namespace Tests\Feature\Api\v2;

use App\Models\BalanceOperation;
use App\Models\OperationCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for BalancesOperationsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\BalancesOperationsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('balance_operations')]
class BalancesOperationsControllerTest extends TestCase
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
    public function it_can_get_filtered_operations()
    {
        BalanceOperation::factory()->count(5)->create([
            'operation' => 'Transfer'
        ]);

        $response = $this->getJson('/api/v2/balance/operations/filtered?search=Transfer&per_page=10');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_all_operations()
    {
        BalanceOperation::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/balance/operations/all');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_operation_by_id()
    {
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Test Operation'
        ]);

        $response = $this->getJson("/api/v2/balance/operations/{$operation->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['operation' => 'Test Operation']);
    }

    #[Test]
    public function it_returns_404_when_operation_not_found()
    {
        $response = $this->getJson('/api/v2/balance/operations/999999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Operation not found']);
    }

    #[Test]
    public function it_can_create_operation()
    {
        $data = [
            'ref' => 'REF-' . uniqid(),
            'operation' => 'New Transfer',
            'direction' => 'IN',
            'balance_id' => 1,
            'operation_category_id' => 1,
        ];

        $response = $this->postJson('/api/v2/balance/operations/', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('balance_operations', [
            'operation' => 'New Transfer'
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating()
    {
        $response = $this->postJson('/api/v2/balance/operations/', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_operation()
    {
        $operation = BalanceOperation::factory()->create([
            'operation' => 'Original Operation',
            'direction' => 'IN'
        ]);

        $updateData = [
            'operation' => 'Updated Operation',
            'direction' => 'OUT'
        ];

        $response = $this->putJson("/api/v2/balance/operations/{$operation->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('balance_operations', [
            'id' => $operation->id,
            'operation' => 'Updated Operation'
        ]);
    }

    #[Test]
    public function it_can_delete_operation()
    {
        $operation = BalanceOperation::factory()->create();

        $response = $this->deleteJson("/api/v2/balance/operations/{$operation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('balance_operations', [
            'id' => $operation->id
        ]);
    }

    #[Test]
    public function it_can_get_categories()
    {
        OperationCategory::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/balance/operations/categories');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_get_category_name()
    {
        $category = OperationCategory::factory()->create([
            'name' => 'Test Category'
        ]);

        $response = $this->getJson("/api/v2/balance/operations/category/{$category->id}/name");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Test Category']);
    }
}

