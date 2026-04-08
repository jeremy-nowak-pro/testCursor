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

        $this->actingAs($user);

        $first = $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $this->assertMutationResponse($first->getStatusCode());

        $second = $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertMutationResponse($second->getStatusCode());

        $cart = Cart::query()->where('user_id', $user->id)->whereNull('session_id')->firstOrFail();

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'currency' => 'EUR',
        ]);
        $this->assertSame(1, CartItem::query()->where('cart_id', $cart->id)->count());
    }

    public function test_show_cart_returns_payload(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $cart = Cart::query()->create([
            'user_id' => $user->id,
            'session_id' => null,
            'currency' => 'EUR',
        ]);

        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'unit_price' => $product->price,
            'currency' => $product->currency,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->get('/cart/state')
            ->assertOk()
            ->assertJsonPath('data.items.0.id', $item->id)
            ->assertJsonPath('data.currency', 'EUR');
    }

    public function test_update_item_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $user->id, 'session_id' => null, 'currency' => 'EUR']);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'unit_price' => $product->price,
            'currency' => $product->currency,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->patch("/cart/items/{$item->id}", ['quantity' => 4]);
        $this->assertMutationResponse($response->getStatusCode());

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 4,
        ]);
    }

    public function test_delete_item_from_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::query()->firstOrFail();
        $cart = Cart::query()->create(['user_id' => $user->id, 'session_id' => null, 'currency' => 'EUR']);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'unit_price' => $product->price,
            'currency' => $product->currency,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->delete("/cart/items/{$item->id}");
        $this->assertMutationResponse($response->getStatusCode());

        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_add_item_returns_422_when_payload_is_invalid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/cart/items', [
                'product_id' => 999999,
                'quantity' => 0,
            ])
            ->assertStatus(422);
    }

    public function test_user_cannot_update_or_delete_another_user_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::query()->firstOrFail();

        $cart = Cart::query()->create(['user_id' => $owner->id, 'session_id' => null, 'currency' => 'EUR']);
        $item = CartItem::query()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'unit_price' => $product->price,
            'currency' => $product->currency,
            'quantity' => 1,
        ]);

        $this->actingAs($otherUser)
            ->patchJson("/cart/items/{$item->id}", ['quantity' => 2])
            ->assertNotFound();

        $this->actingAs($otherUser)
            ->deleteJson("/cart/items/{$item->id}")
            ->assertNotFound();
    }

    private function assertMutationResponse(int $statusCode): void
    {
        $this->assertTrue(
            in_array($statusCode, [200, 302], true),
            "Expected mutation status 200 or 302, got {$statusCode}."
        );
    }
}
