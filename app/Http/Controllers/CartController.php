<?php

namespace App\Http\Controllers;


use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CartController extends Controller
{
n response()->json(['data' => $this->cartPayload($cart)]);
    public function __construct(private readonly CartService $cartService) {}

    public function show(Request $request): JsonResponse
    {
        $cart = $this->cartService->findCurrentCart($request->user(), $request->session()->getId());
        if (! $cart) {
            return response()->json(['data' => ['items' => [], 'subtotal' => 0, 'currency' => 'EUR']]);
        }

        $cart->load('items.product');

        return response()->json(['data' => $this->cartPayload($cart->items->all())]);
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

        $product = Product::query()->find($validated['product_id']);
        if (! $product) {
            return response()->json([
                'error' => ['code' => 'PRODUCT_NOT_FOUND', 'message' => 'Product not found.'],
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $cart = $this->cartService->resolveCurrentCart($request->user(), $request->session()->getId());
        if (! $request->user()) {
            $request->session()->put('guest_cart_id', $cart->id);
        }
        $cart = $this->cartService->addItem($cart, $product, (int) $validated['quantity']);

        return response()->json(['data' => $this->cartPayload($cart->items->all())]);
    }

    public function updateItem(Request $request, CartItem $item): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        $validated = $validator->validated();

        if (! $this->ownsItem($request, $item)) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        $this->cartService->updateItemQuantity($item, (int) $validated['quantity']);
        $item->load('cart.items.product');

        return response()->json(['data' => $this->cartPayload($item->cart->items->all())]);
    }

    public function destroyItem(Request $request, CartItem $item): JsonResponse
    {
        if (! $this->ownsItem($request, $item)) {
            abort(HttpResponse::HTTP_NOT_FOUND);
        }

        $cart = $item->cart()->with('items.product')->first();
        $this->cartService->removeItem($item);
        $cart?->refresh()->load('items.product');

        return response()->json(['data' => $this->cartPayload($cart?->items?->all() ?? [])]);
    }

    /**
     * @return array<string, mixed>
     */
    private function cartPayload(array $items): array
    {
        $normalized = collect($items)->map(function (CartItem $item): array {
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

        $subtotal = (float) $normalized->sum('line_total');

        return [
            'items' => $normalized,
            'subtotal' => round($subtotal, 2),
            'currency' => $normalized->first()['currency'] ?? 'EUR',
        ];
    }

    private function ownsItem(Request $request, CartItem $item): bool
    {
        $item->loadMissing('cart');
        $cart = $item->cart;
        if (! $cart) {
            return false;
        }

        if ($request->user()) {
            return (int) $cart->user_id === (int) $request->user()->getAuthIdentifier();
        }

        return $cart->user_id === null && $cart->session_id === $request->session()->getId();
    }
}
