import { useState } from 'react';
import axios from 'axios';
import { writeCartToStorage } from '../../lib/cartStorage';

export default function AddToCartButton({
    productId,
    quantity = 1,
    className = '',
    disabled = false,
    onSuccess,
}) {
    const [isLoading, setIsLoading] = useState(false);
    const [status, setStatus] = useState(null);
    const [message, setMessage] = useState('');

    const handleAdd = async () => {
        if (!productId || disabled || isLoading) return;

        setIsLoading(true);
        setStatus(null);
        setMessage('');

        try {
            const response = await axios.post('/cart/items', {
                product_id: productId,
                quantity,
            });

            if (response?.data?.data) {
                writeCartToStorage(response.data.data);
            }

            setStatus('success');
            setMessage('Ajoute au panier.');
            onSuccess?.(response?.data?.data);
        } catch (error) {
            setStatus('error');
            setMessage(
                error?.response?.data?.error?.message ||
                    error?.response?.data?.message ||
                    "Echec de l'ajout au panier.",
            );
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="space-y-2">
            <button
                type="button"
                onClick={handleAdd}
                disabled={disabled || isLoading}
                className={`rounded bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 ${className}`}
            >
                {isLoading ? 'Ajout...' : 'Ajouter au panier'}
            </button>

            {status === 'success' ? (
                <p className="text-xs text-emerald-700">{message}</p>
            ) : null}
            {status === 'error' ? <p className="text-xs text-red-700">{message}</p> : null}
        </div>
    );
}
