<?php

namespace Tests\Feature\Api\v2;

use App\Models\DealProductChange;
use App\Models\Deal;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for DealProductChangeController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\DealProductChangeController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('deal_product_changes')]
class DealProductChangeControllerTest extends TestCase
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
    public function it_can_get_filtered_product_changes()
    {
        DealProductChange::factory()->count(10)->create();

        $response = $this->getJson('/api/v2/deal-product-changes?per_page=15');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_filter_by_deal_id()
    {
        $deal = Deal::factory()->create();
        DealProductChange::factory()->count(3)->create(['deal_id' => $deal->id]);

        $response = $this->getJson("/api/v2/deal-product-changes?deal_id={$deal->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_by_action()
    {
        DealProductChange::factory()->count(3)->create(['action' => 'added']);

        $response = $this->getJson('/api/v2/deal-product-changes?action=added');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_by_date_range()
    {
        $response = $this->getJson('/api/v2/deal-product-changes?from_date=2024-01-01&to_date=2024-12-31');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_change_by_id()
    {
        $change = DealProductChange::factory()->create();

        $response = $this->getJson("/api/v2/deal-product-changes/{$change->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_change()
    {
        $response = $this->getJson('/api/v2/deal-product-changes/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_get_statistics()
    {
        $deal = Deal::factory()->create();
        DealProductChange::factory()->count(5)->create(['deal_id' => $deal->id]);

        $response = $this->getJson("/api/v2/deal-product-changes/statistics?deal_id={$deal->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_create_product_change()
    {
        $deal = Deal::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'added',
            'changed_by' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/deal-product-changes', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_product_change_creation()
    {
        $response = $this->postJson('/api/v2/deal-product-changes', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['deal_id', 'item_id', 'action']);
    }

    #[Test]
    public function it_validates_action_field()
    {
        $deal = Deal::factory()->create();
        $item = Item::factory()->create();

        $data = [
            'deal_id' => $deal->id,
            'item_id' => $item->id,
            'action' => 'invalid'
        ];

        $response = $this->postJson('/api/v2/deal-product-changes', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['action']);
    }

    #[Test]
    public function it_can_create_bulk_changes()
    {
        $deal = Deal::factory()->create();
        $items = Item::factory()->count(3)->create();

        $data = [
            'deal_id' => $deal->id,
            'item_ids' => $items->pluck('id')->toArray(),
            'action' => 'added',
            'changed_by' => $this->user->id
        ];

        $response = $this->postJson('/api/v2/deal-product-changes/bulk', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_bulk_creation()
    {
        $response = $this->postJson('/api/v2/deal-product-changes/bulk', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['deal_id', 'item_ids', 'action']);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/deal-product-changes?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

