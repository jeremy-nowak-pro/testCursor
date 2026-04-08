import { router } from '@inertiajs/react';

function toNumber(value) {
    if (value === '' || value === null || value === undefined) return undefined;
    const parsed = Number(value);
    return Number.isNaN(parsed) ? undefined : parsed;
}

export default function FilterSidebar({
    categorySlug,
    availableFilters,
    selectedFilters,
}) {
    const minPrice = selectedFilters?.min_price ?? '';
    const maxPrice = selectedFilters?.max_price ?? '';
    const type = selectedFilters?.type ?? '';
    const format = selectedFilters?.format ?? '';

    const typeOptions = availableFilters?.type ?? [];
    const formatOptions = availableFilters?.format ?? [];
    const price = availableFilters?.price ?? {};

    const submitFilters = (event) => {
        event.preventDefault();
        const formData = new FormData(event.currentTarget);

        const payload = {
            min_price: toNumber(formData.get('min_price')),
            max_price: toNumber(formData.get('max_price')),
            type: formData.get('type') || undefined,
            format: formData.get('format') || undefined,
        };

        router.get(`/categories/${categorySlug}/products`, payload, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    };

    const clearFilters = () => {
        router.get(
            `/categories/${categorySlug}/products`,
            {},
            { preserveState: true, preserveScroll: true, replace: true },
        );
    };

    return (
        <aside className="rounded-lg border border-slate-200 bg-white p-4">
            <h2 className="mb-4 text-lg font-semibold text-slate-900">Filtres</h2>

            <form onSubmit={submitFilters} className="space-y-4">
                <div className="space-y-2">
                    <label className="text-sm font-medium text-slate-700" htmlFor="min_price">
                        Prix min
                    </label>
                    <input
                        id="min_price"
                        name="min_price"
                        type="number"
                        min={price?.min ?? 0}
                        defaultValue={minPrice}
                        className="w-full rounded border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    />
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium text-slate-700" htmlFor="max_price">
                        Prix max
                    </label>
                    <input
                        id="max_price"
                        name="max_price"
                        type="number"
                        min={price?.min ?? 0}
                        defaultValue={maxPrice}
                        className="w-full rounded border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    />
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium text-slate-700" htmlFor="type">
                        Type
                    </label>
                    <select
                        id="type"
                        name="type"
                        defaultValue={type}
                        className="w-full rounded border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >
                        <option value="">Tous</option>
                        {typeOptions.map((option) => (
                            <option key={option} value={option}>
                                {option}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="space-y-2">
                    <label className="text-sm font-medium text-slate-700" htmlFor="format">
                        Format
                    </label>
                    <select
                        id="format"
                        name="format"
                        defaultValue={format}
                        className="w-full rounded border-slate-300 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500"
                    >
                        <option value="">Tous</option>
                        {formatOptions.map((option) => (
                            <option key={option} value={option}>
                                {option}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="flex gap-2">
                    <button
                        type="submit"
                        className="rounded bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800"
                    >
                        Appliquer
                    </button>
                    <button
                        type="button"
                        onClick={clearFilters}
                        className="rounded border border-slate-300 px-3 py-2 text-sm hover:bg-slate-100"
                    >
                        Reinitialiser
                    </button>
                </div>
            </form>
        </aside>
    );
}
