<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = $this->activeCart((int) $request->user()->id);
        $cart->load('items.product');

        return response()->json(['data' => $this->cartPayload($cart)]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $validated = $validator->validated();
        $product = Product::query()->findOrFail((int) $validated['product_id']);
        $cart = $this->activeCart((int) $request->user()->id);

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->quantity = ($item->quantity ?? 0) + (int) $validated['quantity'];
        $item->unit_price = $item->exists ? $item->unit_price : $product->price;
        $item->currency = $item->exists ? $item->currency : $product->currency;
        $item->save();

        $cart->refresh()->load('items.product');

        return response()->json(['data' => $this->cartPayload($cart)]);
    }

    public function updateItem(Request $request, CartItem $item): JsonResponse
    {
        $this->assertOwnership($request, $item);

        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $item->update(['quantity' => (int) $validator->validated()['quantity']]);
        $cart = $item->cart()->with('items.product')->firstOrFail();

        return response()->json(['data' => $this->cartPayload($cart)]);
    }

    public function destroyItem(Request $request, CartItem $item): JsonResponse
    {
        $this->assertOwnership($request, $item);

        $cart = $item->cart()->firstOrFail();
        $item->delete();
        $cart->refresh()->load('items.product');

        return response()->json(['data' => $this->cartPayload($cart)]);
    }

    private function activeCart(int $userId): Cart
    {
        return Cart::query()->firstOrCreate(['user_id' => $userId]);
    }

    private function assertOwnership(Request $request, CartItem $item): void
    {
        $item->loadMissing('cart');

        if ((int) $item->cart->user_id !== (int) $request->user()->id) {
            abort(HttpResponse::HTTP_FORBIDDEN);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function cartPayload(Cart $cart): array
    {
        $items = $cart->items->map(function (CartItem $item): array {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => [
                    'name' => $item->product->name,
                    'slug' => $item->product->slug,
                ],
                'unit_price' => (float) $item->unit_price,
                'currency' => $item->currency,
                'quantity' => $item->quantity,
                'line_total' => round((float) $item->unit_price * $item->quantity, 2),
            ];
        })->values();

        return [
            'id' => $cart->id,
            'items' => $items,
            'subtotal' => (float) $items->sum('line_total'),
            'currency' => $items->first()['currency'] ?? 'EUR',
        ];
    }
}
