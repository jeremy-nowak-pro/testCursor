import AddToCartButton from '../cart/AddToCartButton';

export default function ProductCard({ product }) {
    if (!product) return null;

    return (
        <article className="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <a href={`/products/${product.slug}`} className="block">
                <img
                    src={
                        product.thumbnail ||
                        'https://placehold.co/800x500/e2e8f0/334155?text=Produit'
                    }
                    alt={product.name}
                    className="h-44 w-full object-cover"
                />
            </a>

            <div className="space-y-2 p-4">
                <div className="flex items-start justify-between gap-3">
                    <h3 className="text-base font-semibold text-slate-900">
                        <a href={`/products/${product.slug}`} className="hover:underline">
                            {product.name}
                        </a>
                    </h3>
                    <span className="whitespace-nowrap text-sm font-semibold text-slate-700">
                        {product.price} {product.currency}
                    </span>
                </div>

                <div className="flex flex-wrap gap-2 text-xs text-slate-600">
                    <span className="rounded bg-slate-100 px-2 py-1">{product.type}</span>
                    <span className="rounded bg-slate-100 px-2 py-1">{product.format}</span>
                </div>

                <AddToCartButton productId={product.id} className="w-full" />
            </div>
        </article>
    );
}
