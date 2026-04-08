<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCartOnLogin
{
    public function __construct(private readonly CartService $cartService) {}

    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $sessionId = session()->getId();
        $guestCartId = session()->get('guest_cart_id');
        if (! $sessionId && ! $guestCartId) {
            return;
        }

        $this->cartService->mergeGuestCartIntoUserCart($event->user, $sessionId, $guestCartId);
        session()->forget('guest_cart_id');
    }
}
