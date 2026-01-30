<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Platform;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
    }

    /**
     * Test getUserCart returns cart when exists
     */
    public function test_get_user_cart_returns_cart_when_exists()
    {
        // Arrange
        $user = User::factory()->create(['email' => 'carttest1_' . uniqid() . '@example.com']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->cartService->getUserCart($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertEquals($cart->id, $result->id);
        $this->assertEquals($user->id, $result->user_id);
    }

    /**
     * Test getUserCart returns null when cart does not exist
     */
    public function test_get_user_cart_returns_null_when_not_exists()
    {
        // Arrange
        $user = User::factory()->create(['email' => 'carttest2_' . uniqid() . '@example.com']);

        // Act
        $result = $this->cartService->getUserCart($user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getUserCart returns correct cart for specific user
     */
    public function test_get_user_cart_returns_correct_cart_for_user()
    {
        // Arrange
        $user1 = User::factory()->create(['email' => 'carttest3a_' . uniqid() . '@example.com']);
        $user2 = User::factory()->create(['email' => 'carttest3b_' . uniqid() . '@example.com']);
        $cart1 = Cart::factory()->create(['user_id' => $user1->id]);
        $cart2 = Cart::factory()->create(['user_id' => $user2->id]);

        // Act
        $result = $this->cartService->getUserCart($user1->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($cart1->id, $result->id);
        $this->assertNotEquals($cart2->id, $result->id);
    }

    /**
     * Test isCartEmpty returns true when cart does not exist
     */
    public function test_is_cart_empty_returns_true_when_cart_not_exists()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->cartService->isCartEmpty($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test isCartEmpty returns true when cart has no items
     */
    public function test_is_cart_empty_returns_true_when_no_items()
    {
        // Arrange
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->cartService->isCartEmpty($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test isCartEmpty returns false when cart has items
     */
    public function test_is_cart_empty_returns_false_when_has_items()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create(['cart_id' => $cart->id]);

        // Act
        $result = $this->cartService->isCartEmpty($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test isCartEmpty returns false when cart has multiple items
     */
    public function test_is_cart_empty_returns_false_when_has_multiple_items()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->count(3)->create(['cart_id' => $cart->id]);

        // Act
        $result = $this->cartService->isCartEmpty($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getCartItemsGroupedByPlatform returns empty array when no cart
     */
    public function test_get_cart_items_grouped_by_platform_returns_empty_when_no_cart()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->cartService->getCartItemsGroupedByPlatform($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test getCartItemsGroupedByPlatform returns empty array when cart is empty
     */
    public function test_get_cart_items_grouped_by_platform_returns_empty_when_cart_empty()
    {
        // Arrange
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->cartService->getCartItemsGroupedByPlatform($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test getCartItemsGroupedByPlatform groups items by platform correctly
     */
    public function test_get_cart_items_grouped_by_platform_groups_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();

        $item1 = Item::factory()->create(['platform_id' => $platform1->id]);
        $item2 = Item::factory()->create(['platform_id' => $platform2->id]);
        $item3 = Item::factory()->create(['platform_id' => $platform1->id]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item3->id]);

        // Act
        $result = $this->cartService->getCartItemsGroupedByPlatform($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey($platform1->id, $result);
        $this->assertArrayHasKey($platform2->id, $result);
        $this->assertCount(2, $result[$platform1->id]);
        $this->assertCount(1, $result[$platform2->id]);
    }

    /**
     * Test getCartItemsGroupedByPlatform handles items with deals
     */
    public function test_get_cart_items_grouped_by_platform_handles_deals()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform = Platform::factory()->create();
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);

        $item = Item::factory()->create(['deal_id' => $deal->id, 'platform_id' => null]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item->id]);

        // Act
        $result = $this->cartService->getCartItemsGroupedByPlatform($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($platform->id, $result);
        $this->assertCount(1, $result[$platform->id]);
    }

    /**
     * Test getCartItemsGroupedByPlatform returns only items with valid platforms
     */
    public function test_get_cart_items_grouped_by_platform_filters_invalid_items()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform = Platform::factory()->create();

        $item1 = Item::factory()->create(['platform_id' => $platform->id]);
        $item2 = Item::factory()->create(['platform_id' => null, 'deal_id' => null]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);

        // Act
        $result = $this->cartService->getCartItemsGroupedByPlatform($user->id);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey($platform->id, $result);
    }

    /**
     * Test getUniquePlatformsCount returns correct count
     */
    public function test_get_unique_platforms_count_returns_correct_count()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $item1 = Item::factory()->create(['platform_id' => $platform1->id]);
        $item2 = Item::factory()->create(['platform_id' => $platform2->id]);
        $item3 = Item::factory()->create(['platform_id' => $platform1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item3->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(2, $result);
    }

    /**
     * Test getUniquePlatformsCount returns zero when no cart
     */
    public function test_get_unique_platforms_count_returns_zero_when_no_cart()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getUniquePlatformsCount returns zero when cart is empty
     */
    public function test_get_unique_platforms_count_returns_zero_when_cart_empty()
    {
        // Arrange
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getUniquePlatformsCount returns one when all items from same platform
     */
    public function test_get_unique_platforms_count_returns_one_when_same_platform()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $platform = Platform::factory()->create();

        $item1 = Item::factory()->create(['platform_id' => $platform->id]);
        $item2 = Item::factory()->create(['platform_id' => $platform->id]);
        $item3 = Item::factory()->create(['platform_id' => $platform->id]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item3->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(1, $result);
    }

    /**
     * Test getUniquePlatformsCount handles items with deals correctly
     */
    public function test_get_unique_platforms_count_handles_deals()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();

        $deal = Deal::factory()->create(['platform_id' => $platform2->id]);

        $item1 = Item::factory()->create(['platform_id' => $platform1->id]);
        $item2 = Item::factory()->create(['deal_id' => $deal->id, 'platform_id' => null]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(2, $result);
    }

    /**
     * Test getUniquePlatformsCount ignores items without platform
     */
    public function test_get_unique_platforms_count_ignores_items_without_platform()
    {
        // Arrange
        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform = Platform::factory()->create();

        $item1 = Item::factory()->create(['platform_id' => $platform->id]);
        $item2 = Item::factory()->create(['platform_id' => null, 'deal_id' => null]);
        $item3 = Item::factory()->create(['platform_id' => null, 'deal_id' => null]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item3->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(1, $result);
    }

    /**
     * Test getUniquePlatformsCount with multiple platforms
     */
    public function test_get_unique_platforms_count_with_multiple_platforms()
    {
        // Arrange
        $user = User::factory()->create(['email' => 'carttest19_' . uniqid() . '@example.com']);
        $cart = Cart::factory()->create(['user_id' => $user->id]);

        $platform1 = Platform::factory()->create();
        $platform2 = Platform::factory()->create();
        $platform3 = Platform::factory()->create();

        $item1 = Item::factory()->create(['platform_id' => $platform1->id]);
        $item2 = Item::factory()->create(['platform_id' => $platform2->id]);
        $item3 = Item::factory()->create(['platform_id' => $platform3->id]);
        $item4 = Item::factory()->create(['platform_id' => $platform1->id]);
        $item5 = Item::factory()->create(['platform_id' => $platform2->id]);

        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item1->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item2->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item3->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item4->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'item_id' => $item5->id]);

        // Act
        $result = $this->cartService->getUniquePlatformsCount($user->id);

        // Assert
        $this->assertEquals(3, $result);
    }
}
