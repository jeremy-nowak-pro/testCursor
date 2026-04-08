import { Head } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import ShopLayout from '../../Components/ShopLayout';
import { readCartFromStorage, writeCartToStorage } from '../../lib/cartStorage';

function toNumber(value, fallback = 0) {
    const n = Number(value);
    return Number.isFinite(n) ? n : fallback;
}

export default function CartIndex({ data }) {
    const [cart, setCart] = useState(data || readCartFromStorage() || {});
    const [error, setError] = useState('');
    const [isUpdating, setIsUpdating] = useState(false);

    const items = Array.isArray(cart?.items) ? cart.items : [];
    const currency = cart?.currency || 'EUR';

    const computedTotal = useMemo(() => {
        if (cart?.subtotal != null) return toNumber(cart.subtotal);
        return items.reduce((sum, item) => {
            const qty = toNumber(item.quantity ?? item.qty, 1);
            const unitPrice = toNumber(item.unit_price ?? item.price);
            return sum + qty * unitPrice;
        }, 0);
    }, [cart, items]);

    const syncCart = (nextCart) => {
        setCart(nextCart || {});
        writeCartToStorage(nextCart || {});
    };

    useEffect(() => {
        let active = true;

        const loadCart = async () => {
            try {
                const response = await axios.get('/cart/state');
                if (!active) return;
                if (response?.data?.data) {
                    syncCart(response.data.data);
                }
            } catch {
                // Keep last known cart (props/localStorage) if state endpoint fails.
            }
        };

        loadCart();
        return () => {
            active = false;
        };
    }, []);

    const handleUpdateQuantity = async (item, value) => {
        const quantity = Math.max(1, toNumber(value, 1));
        setIsUpdating(true);
        setError('');

        try {
            const response = await axios.patch(`/cart/items/${item.id}`, { quantity });
            syncCart(response?.data?.data);
        } catch {
            setError("Impossible de mettre a jour la quantite pour l'instant.");
        } finally {
            setIsUpdating(false);
        }
    };

    const handleRemove = async (item) => {
        setIsUpdating(true);
        setError('');

        try {
            const response = await axios.delete(`/cart/items/${item.id}`);
            syncCart(response?.data?.data);
        } catch {
            setError("Impossible de retirer cet article pour l'instant.");
        } finally {
            setIsUpdating(false);
        }
    };

    return (
        <>
            <Head title="Panier" />
            <ShopLayout title="Panier">
                <div className="space-y-4">
                    {error ? (
                        <div className="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            {error}
                        </div>
                    ) : null}

                    {items.length === 0 ? (
                        <div className="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-600">
                            Votre panier est vide.
                        </div>
                    ) : (
                        <div className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                            <table className="min-w-full divide-y divide-slate-200 text-sm">
                                <thead className="bg-slate-50 text-left text-slate-600">
                                    <tr>
                                        <th className="px-4 py-3 font-medium">Produit</th>
                                        <th className="px-4 py-3 font-medium">Qty</th>
                                        <th className="px-4 py-3 font-medium">Prix unitaire</th>
                                        <th className="px-4 py-3 font-medium">Sous-total</th>
                                        <th className="px-4 py-3 font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {items.map((item) => {
                                        const qty = toNumber(item.quantity ?? item.qty, 1);
                                        const unitPrice = toNumber(item.unit_price ?? item.price);
                                        const subTotal =
                                            item.subtotal != null
                                                ? toNumber(item.subtotal)
                                                : unitPrice * qty;

                                        return (
                                            <tr key={item.id}>
                                                <td className="px-4 py-3 font-medium text-slate-900">
                                                    {item.name || item.product_name || 'Produit'}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <input
                                                        type="number"
                                                        min={1}
                                                        defaultValue={qty}
                                                        onBlur={(event) =>
                                                            handleUpdateQuantity(
                                                                item,
                                                                event.target.value,
                                                            )
                                                        }
                                                        disabled={isUpdating}
                                                        className="w-20 rounded border border-slate-300 px-2 py-1"
                                                    />
                                                </td>
                                                <td className="px-4 py-3">
                                                    {unitPrice} {currency}
                                                </td>
                                                <td className="px-4 py-3 font-medium">
                                                    {subTotal} {currency}
                                                </td>
                                                <td className="px-4 py-3">
                                                    <button
                                                        type="button"
                                                        onClick={() => handleRemove(item)}
                                                        disabled={isUpdating}
                                                        className="rounded border border-slate-300 px-2 py-1 text-xs hover:bg-slate-100 disabled:opacity-60"
                                                    >
                                                        Retirer
                                                    </button>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    )}

                    <div className="flex justify-end">
                        <div className="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm">
                            <span className="text-slate-600">Total panier:</span>{' '}
                            <span className="font-semibold text-slate-900">
                                {computedTotal} {currency}
                            </span>
                        </div>
                    </div>
                </div>
            </ShopLayout>
        </>
    );
}
