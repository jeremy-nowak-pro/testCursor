<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CatalogSeeder::class);
    }

    public function test_add_item_merges_quantity_for_same_product(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $response = $this->actingAs($user)->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $this->assertContainsMutationStatus($response->getStatusCode());

        $response2 = $this->actingAs($user)->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertContainsMutationStatus($response2->getStatusCode());

        $cart = Cart::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'currency' => 'EUR',
        ]);
        $this->assertSame(1, CartItem::query()->where('cart_id', $cart->id)->count());
    }

    public function test_show_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
            'currency' => $product->currency,
        ]);

        $this->actingAs($user)
            ->get('/cart')
            ->assertOk()
            ->assertJsonPath('data.id', $cart->id)
            ->assertJsonPath('data.items.0.id', $item->id)
            ->assertJsonPath('data.currency', 'EUR');
    }

    public function test_update_qty(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'currency' => $product->currency,
        ]);

        $response = $this->actingAs($user)->patch("/cart/items/{$item->id}", [
            'quantity' => 4,
        ]);
        $this->assertContainsMutationStatus($response->getStatusCode());

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 4,
        ]);
    }

    public function test_delete_item(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $user->id]);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'currency' => $product->currency,
        ]);

        $response = $this->actingAs($user)->delete("/cart/items/{$item->id}");
        $this->assertContainsMutationStatus($response->getStatusCode());

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_validation_invalid_returns_422(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/cart/items', [
                'product_id' => 999999,
                'quantity' => 0,
            ])
            ->assertStatus(422);
    }

    public function test_forbidden_cross_user_ownership(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $owner->id]);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
            'currency' => $product->currency,
        ]);

        $this->actingAs($other)
            ->patchJson("/cart/items/{$item->id}", ['quantity' => 2])
            ->assertForbidden();

        $this->actingAs($other)
            ->deleteJson("/cart/items/{$item->id}")
            ->assertForbidden();
    }

    private function assertContainsMutationStatus(int $statusCode): void
    {
        $this->assertTrue(
            in_array($statusCode, [200, 302], true),
            "Expected status 200 or 302, got {$statusCode}."
        );
    }
}
