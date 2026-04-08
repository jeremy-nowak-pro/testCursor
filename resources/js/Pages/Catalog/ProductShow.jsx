import { Head } from '@inertiajs/react';
import ShopLayout from '../../Components/ShopLayout';

export default function ProductShow({ data }) {
    const product = data;
    const mainImage = product?.images?.[0];

    return (
        <>
            <Head title={product?.name ? `Produit - ${product.name}` : 'Produit'} />
            <ShopLayout title={product?.name || 'Fiche produit'}>
                {!product ? (
                    <div className="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        Impossible de charger les donnees produit.
                    </div>
                ) : (
                    <div className="grid gap-6 lg:grid-cols-2">
                        <div className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                            <img
                                src={
                                    mainImage ||
                                    'https://placehold.co/1200x800/e2e8f0/334155?text=Produit'
                                }
                                alt={product.name}
                                className="h-full w-full object-cover"
                            />
                        </div>

                        <div className="space-y-4 rounded-xl border border-slate-200 bg-white p-6">
                            <div className="flex flex-wrap items-center gap-2 text-xs">
                                <span className="rounded bg-slate-100 px-2 py-1 text-slate-600">
                                    {product.category}
                                </span>
                                <span className="rounded bg-slate-100 px-2 py-1 text-slate-600">
                                    {product.type}
                                </span>
                                <span className="rounded bg-slate-100 px-2 py-1 text-slate-600">
                                    {product.format}
                                </span>
                            </div>

                            <p className="text-2xl font-bold text-slate-900">
                                {product.price} {product.currency}
                            </p>

                            <p className="text-sm leading-6 text-slate-700">
                                {product.description}
                            </p>

                            <p
                                className={`text-sm font-medium ${
                                    product.in_stock ? 'text-emerald-700' : 'text-red-700'
                                }`}
                            >
                                {product.in_stock ? 'En stock' : 'Rupture de stock'}
                            </p>
                        </div>
                    </div>
                )}
            </ShopLayout>
        </>
    );
}
