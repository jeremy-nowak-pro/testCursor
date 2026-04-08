<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function resolveCurrentCart(?Authenticatable $user, string $sessionId): Cart
    {
        if ($user instanceof User) {
            return Cart::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => null, 'currency' => 'EUR']
            );
        }

        return Cart::query()->firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null],
            ['currency' => 'EUR']
        );
    }

    public function findCurrentCart(?Authenticatable $user, string $sessionId): ?Cart
    {
        if ($user instanceof User) {
            return Cart::query()->where('user_id', $user->id)->first();
        }

        return Cart::query()
            ->whereNull('user_id')
            ->where('session_id', $sessionId)
            ->first();
    }

    public function addItem(Cart $cart, Product $product, int $quantity): Cart
    {
        DB::transaction(function () use ($cart, $product, $quantity): void {
            $item = CartItem::query()
                ->where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->quantity += $quantity;
                $item->unit_price = $item->unit_price ?: $product->price;
                $item->currency = $item->currency ?: $product->currency;
                $item->save();

                return;
            }

            CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'currency' => $product->currency,
            ]);
        });

        return $cart->fresh(['items.product']);
    }

    public function updateItemQuantity(CartItem $item, int $quantity): void
    {
        $item->quantity = $quantity;
        $item->save();
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function mergeGuestCartIntoUserCart(User $user, string $sessionId, ?int $guestCartId = null): void
    {
        DB::transaction(function () use ($user, $sessionId, $guestCartId): void {
            $guestCartQuery = Cart::query()->with('items')->whereNull('user_id')->lockForUpdate();
            $guestCart = $guestCartId
                ? $guestCartQuery->where('id', $guestCartId)->first()
                : $guestCartQuery->where('session_id', $sessionId)->first();

            if (! $guestCart) {
                return;
            }

            $userCart = Cart::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['session_id' => null, 'currency' => 'EUR']
            );

            /** @var Collection<int, CartItem> $guestItems */
            $guestItems = $guestCart->items;
            foreach ($guestItems as $guestItem) {
                $userItem = CartItem::query()
                    ->where('cart_id', $userCart->id)
                    ->where('product_id', $guestItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($userItem) {
                    $userItem->quantity += $guestItem->quantity;
                    $userItem->save();
                    continue;
                }

                CartItem::query()->create([
                    'cart_id' => $userCart->id,
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                    'unit_price' => $guestItem->unit_price,
                    'currency' => $guestItem->currency,
                ]);
            }

            $guestCart->merged_at = now();
            $guestCart->save();
            $guestCart->delete();
        });
    }
}
