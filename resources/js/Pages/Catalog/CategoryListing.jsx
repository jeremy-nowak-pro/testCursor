import { Head } from '@inertiajs/react';
import ShopLayout from '../../Components/ShopLayout';
import FilterSidebar from '../../Components/catalog/FilterSidebar';
import ProductCard from '../../Components/catalog/ProductCard';

export default function CategoryListing({
    data,
    meta,
    errors,
    selected_filters,
    category,
}) {
    const items = Array.isArray(data?.items) ? data.items : [];
    const pagination = meta?.pagination;
    const availableFilters = meta?.available_filters;
    const categoryName = category?.name || category?.slug || 'Categorie';
    const categorySlug = category?.slug || 'unknown';
    const hasFilterError = Boolean(
        errors?.min_price || errors?.max_price || errors?.type || errors?.format,
    );

    return (
        <>
            <Head title={`Categorie - ${categoryName}`} />
            <ShopLayout title={`Categorie: ${categoryName}`}>
                <div className="grid gap-6 lg:grid-cols-[280px_1fr]">
                    <div>
                        <FilterSidebar
                            categorySlug={categorySlug}
                            availableFilters={availableFilters}
                            selectedFilters={selected_filters}
                        />
                    </div>

                    <div className="space-y-4">
                        {hasFilterError ? (
                            <div className="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                                Certains filtres sont invalides. Corrigez les
                                valeurs et relancez la recherche.
                            </div>
                        ) : null}

                        <div className="flex flex-wrap items-center justify-between gap-2 text-sm text-slate-600">
                            <p>{pagination?.total ?? items.length} produit(s)</p>
                            <p>
                                Page {pagination?.page ?? 1}
                                {pagination?.per_page
                                    ? ` • ${pagination.per_page} / page`
                                    : ''}
                            </p>
                        </div>

                        {items.length === 0 ? (
                            <div className="rounded-lg border border-slate-200 bg-white p-6 text-sm text-slate-600">
                                Aucun produit ne correspond aux filtres
                                selectionnes.
                            </div>
                        ) : (
                            <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                {items.map((product) => (
                                    <ProductCard key={product.id} product={product} />
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </ShopLayout>
        </>
    );
}
