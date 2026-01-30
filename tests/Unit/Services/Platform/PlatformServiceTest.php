<?php

namespace Tests\Unit\Services\Platform;

use App\Models\BusinessSector;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Platform;
use App\Models\User;
use App\Services\Platform\PlatformService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class PlatformServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PlatformService $platformService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platformService = new PlatformService();
    }

    /**
     * Test getAll returns all platforms
     */
    public function test_get_all_returns_all_platforms()
    {
        // Arrange
        $initialCount = Platform::count();
        $platforms = Platform::factory()->count(3)->create();

        // Act
        $result = $this->platformService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
        foreach ($platforms as $platform) {
            $this->assertTrue($result->contains('id', $platform->id));
        }
    }

    /**
     * Test getAll returns collection
     */
    public function test_get_all_returns_collection()
    {
        // Act
        $result = $this->platformService->getAll();

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test getById returns platform when exists
     */
    public function test_get_by_id_returns_platform_when_exists()
    {
        // Arrange
        $platform = Platform::factory()->create();

        // Act
        $result = $this->platformService->getById($platform->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platform->id, $result->id);
    }

    /**
     * Test getById returns null when not exists
     */
    public function test_get_by_id_returns_null_when_not_exists()
    {
        // Act
        $result = $this->platformService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test findOrFail returns platform when exists
     */
    public function test_find_or_fail_returns_platform_when_exists()
    {
        // Arrange
        $platform = Platform::factory()->create();

        // Act
        $result = $this->platformService->findOrFail($platform->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platform->id, $result->id);
    }

    /**
     * Test findOrFail throws exception when not exists
     */
    public function test_find_or_fail_throws_exception_when_not_exists()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->platformService->findOrFail(99999);
    }

    /**
     * Test create creates new platform
     */
    public function test_create_creates_new_platform()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Platform',
            'description' => 'Test Description',
            'enabled' => true,
            'type' => 1,
            'created_by' => $user->id,
        ];

        // Act
        $result = $this->platformService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Test Platform', $result->name);
        $this->assertDatabaseHas('platforms', ['name' => 'Test Platform']);
    }

    /**
     * Test update updates existing platform
     */
    public function test_update_updates_existing_platform()
    {
        // Arrange
        $platform = Platform::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->platformService->update($platform->id, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('platforms', ['id' => $platform->id, 'name' => 'New Name']);
    }

    /**
     * Test update returns false when platform not exists
     */
    public function test_update_returns_false_when_not_exists()
    {
        // Act
        $result = $this->platformService->update(99999, ['name' => 'New Name']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete deletes existing platform
     */
    public function test_delete_deletes_existing_platform()
    {
        // Arrange
        $platform = Platform::factory()->create();

        // Act
        $result = $this->platformService->delete($platform->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('platforms', ['id' => $platform->id]);
    }

    /**
     * Test delete returns false when platform not exists
     */
    public function test_delete_returns_false_when_not_exists()
    {
        // Act
        $result = $this->platformService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getPlatformsWithActiveDeals returns only platforms with active deals
     */
    public function test_get_platforms_with_active_deals_returns_correct_platforms()
    {
        // Arrange
        $businessSector = BusinessSector::factory()->create();
        $platform1 = Platform::factory()->create([
            'business_sector_id' => $businessSector->id,
            'enabled' => true
        ]);
        $platform2 = Platform::factory()->create([
            'business_sector_id' => $businessSector->id,
            'enabled' => true
        ]);

        Deal::factory()->create([
            'platform_id' => $platform1->id,
            'status' => 2,
            'validated' => true
        ]);

        // Act
        $result = $this->platformService->getPlatformsWithActiveDeals($businessSector->id);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($platform1->id, $result->first()->id);
    }

    /**
     * Test getPlatformsWithActiveDeals returns empty for disabled platforms
     */
    public function test_get_platforms_with_active_deals_ignores_disabled_platforms()
    {
        // Arrange
        $businessSector = BusinessSector::factory()->create();
        $platform = Platform::factory()->create([
            'business_sector_id' => $businessSector->id,
            'enabled' => false
        ]);

        Deal::factory()->create([
            'platform_id' => $platform->id,
            'status' => 2,
            'validated' => true
        ]);

        // Act
        $result = $this->platformService->getPlatformsWithActiveDeals($businessSector->id);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test getItemsFromEnabledPlatforms returns items from enabled platforms
     */
    public function test_get_items_from_enabled_platforms_returns_correct_items()
    {
        // Arrange
        $businessSector = BusinessSector::factory()->create();
        $platform = Platform::factory()->create([
            'business_sector_id' => $businessSector->id,
            'enabled' => true
        ]);

        $deal = Deal::factory()->create([
            'platform_id' => $platform->id,
            'status' => 2,
            'validated' => true
        ]);

        $item = Item::factory()->create([
            'deal_id' => $deal->id,
            'platform_id' => $platform->id
        ]);

        // Act
        $result = $this->platformService->getItemsFromEnabledPlatforms($businessSector->id);

        // Assert
        $this->assertGreaterThan(0, $result->count());
    }

    /**
     * Test getPlatformsWithUserPurchases returns platforms where user made purchases
     */
    public function test_get_platforms_with_user_purchases_returns_correct_platforms()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);
        $item = Item::factory()->create(['deal_id' => $deal->id, 'platform_id' => $platform->id]);
        $order = Order::factory()->create(['user_id' => $user->id]);
        OrderDetail::factory()->create([
            'order_id' => $order->id,
            'item_id' => $item->id
        ]);

        // Act
        $result = $this->platformService->getPlatformsWithUserPurchases($user->id);

        // Assert
        $this->assertGreaterThan(0, $result->count());
    }

    /**
     * Test getPlatformsWithUserPurchases returns empty when no purchases
     */
    public function test_get_platforms_with_user_purchases_returns_empty_when_no_purchases()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->platformService->getPlatformsWithUserPurchases($user->id);

        // Assert
        $this->assertCount(0, $result);
    }
}
