<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CartAuthMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_add_then_login_merges_into_user_cart(): void
    {
        $product = Product::factory()->create(['price' => 15.50, 'currency' => 'EUR']);
        $existingProduct = Product::factory()->create(['price' => 8.00, 'currency' => 'EUR']);
        $user = User::factory()->create([
            'email' => 'merge@example.test',
            'password' => Hash::make('password123'),
        ]);

        $userCart = Cart::query()->create(['user_id' => $user->id, 'currency' => 'EUR']);
        CartItem::query()->create([
            'cart_id' => $userCart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 15.50,
            'currency' => 'EUR',
        ]);
        CartItem::query()->create([
            'cart_id' => $userCart->id,
            'product_id' => $existingProduct->id,
            'quantity' => 2,
            'unit_price' => 8.00,
            'currency' => 'EUR',
        ]);

        $this->postJson('/cart/items', ['product_id' => $product->id, 'quantity' => 3])->assertOk();

        $this->post('/login', [
            'email' => 'merge@example.test',
            'password' => 'password123',
        ])->assertRedirect('/dashboard');

        $mergedCart = Cart::query()->where('user_id', $user->id)->firstOrFail();
        $mergedItem = CartItem::query()
            ->where('cart_id', $mergedCart->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        $this->assertSame(4, $mergedItem->quantity);
        $this->assertDatabaseMissing('carts', [
            'session_id' => session()->getId(),
            'user_id' => null,
        ]);
    }

    public function test_merge_does_not_create_duplicate_product_rows(): void
    {
        $product = Product::factory()->create(['price' => 19.99, 'currency' => 'EUR']);
        $user = User::factory()->create([
            'email' => 'dedupe@example.test',
            'password' => Hash::make('password123'),
        ]);

        Cart::query()->create(['user_id' => $user->id, 'currency' => 'EUR']);
        $this->postJson('/cart/items', ['product_id' => $product->id, 'quantity' => 1])->assertOk();

        $this->post('/login', [
            'email' => 'dedupe@example.test',
            'password' => 'password123',
        ])->assertRedirect('/dashboard');

        $cart = Cart::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertSame(1, CartItem::query()->where('cart_id', $cart->id)->where('product_id', $product->id)->count());
    }

    public function test_cart_item_operations_are_owned_by_current_user(): void
    {
        $product = Product::factory()->create(['price' => 11.00, 'currency' => 'EUR']);
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $ownerCart = Cart::query()->create(['user_id' => $owner->id, 'currency' => 'EUR']);
        $item = CartItem::query()->create([
            'cart_id' => $ownerCart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 11.00,
            'currency' => 'EUR',
        ]);

        $this->actingAs($attacker)
            ->patchJson("/cart/items/{$item->id}", ['quantity' => 4])
            ->assertNotFound();

        $this->actingAs($attacker)
            ->deleteJson("/cart/items/{$item->id}")
            ->assertNotFound();
    }

    public function test_invalid_payload_returns_422_and_product_missing_returns_422(): void
    {
        $this->postJson('/cart/items', ['product_id' => null, 'quantity' => 0])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'quantity']);

        $this->postJson('/cart/items', ['product_id' => 999999, 'quantity' => 1])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }
}
