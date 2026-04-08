const CART_STORAGE_KEY = 'catalog_mvp_cart';

export function readCartFromStorage() {
    if (typeof window === 'undefined') return null;

    try {
        const raw = window.localStorage.getItem(CART_STORAGE_KEY);
        if (!raw) return null;
        return JSON.parse(raw);
    } catch {
        return null;
    }
}

export function writeCartToStorage(cartData) {
    if (typeof window === 'undefined') return;

    try {
        window.localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartData));
    } catch {
        // Ignore storage failures in restricted browsers.
    }
}
