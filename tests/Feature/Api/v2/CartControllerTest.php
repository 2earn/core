<?php

namespace Tests\Feature\Api\v2;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
      use DatabaseTransactions;

      protected User $user;

      protected function setUp(): void
      {
            parent::setUp();
            $this->user = User::factory()->create();
      }

      /**
       * Test getting user cart
       */
      public function test_get_user_cart()
      {
            $cart = Cart::factory()->create(['user_id' => $this->user->id]);
            $item = Item::factory()->create();
            CartItem::factory()->create([
                  'cart_id' => $cart->id,
                  'item_id' => $item->id,
                  'qty' => 2,
                  'unit_price' => 10,
                  'total_amount' => 20
            ]);

            $cart->update([
                  'total_cart_quantity' => 2,
                  'total_cart' => 20
            ]);

            $response = $this->actingAs($this->user, 'sanctum')
                  ->getJson("/api/v2/carts/user/{$this->user->id}");

            $response->assertStatus(200)
                  ->assertJsonPath('status', true)
                  ->assertJsonPath('data.total_cart_quantity', 2);
      }

      /**
       * Test adding item to cart
       */
      public function test_add_item_to_cart()
      {
            $item = Item::factory()->create(['price' => 100]);

            $response = $this->actingAs($this->user, 'sanctum')
                  ->postJson("/api/v2/carts/user/{$this->user->id}/add", [
                        'item_id' => $item->id,
                        'qty' => 3
                  ]);

            $response->assertStatus(201);
            $this->assertDatabaseHas('carts', ['user_id' => $this->user->id, 'total_cart_quantity' => 3]);
      }

      /**
       * Test updating item quantity
       */
      public function test_update_item_quantity()
      {
            $cart = Cart::factory()->create(['user_id' => $this->user->id]);
            $item = Item::factory()->create(['price' => 50]);
            $cartItem = CartItem::factory()->create([
                  'cart_id' => $cart->id,
                  'item_id' => $item->id,
                  'qty' => 1,
                  'unit_price' => 50,
                  'total_amount' => 50
            ]);

            $response = $this->actingAs($this->user, 'sanctum')
                  ->putJson("/api/v2/carts/user/{$this->user->id}/items/{$item->id}", [
                        'qty' => 5
                  ]);

            $response->assertStatus(200);
            $this->assertDatabaseHas('cart_items', ['id' => $cartItem->id, 'qty' => 5, 'total_amount' => 250]);
      }

      /**
       * Test removing item from cart
       */
      public function test_remove_item_from_cart()
      {
            $cart = Cart::factory()->create(['user_id' => $this->user->id]);
            $item = Item::factory()->create();
            $cartItem = CartItem::factory()->create([
                  'cart_id' => $cart->id,
                  'item_id' => $item->id
            ]);

            $response = $this->actingAs($this->user, 'sanctum')
                  ->deleteJson("/api/v2/carts/user/{$this->user->id}/items/{$item->id}");

            $response->assertStatus(200);
            $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
      }

      /**
       * Test clearing cart
       */
      public function test_clear_cart()
      {
            $cart = Cart::factory()->create(['user_id' => $this->user->id]);
            CartItem::factory()->count(3)->create(['cart_id' => $cart->id]);

            $response = $this->actingAs($this->user, 'sanctum')
                  ->deleteJson("/api/v2/carts/user/{$this->user->id}");

            $response->assertStatus(200);
            $this->assertEquals(0, CartItem::where('cart_id', $cart->id)->count());
      }
}
