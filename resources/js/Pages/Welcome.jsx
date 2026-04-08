import { Head } from '@inertiajs/react';
import ShopLayout from '../Components/ShopLayout';

const defaultCategories = [
    { name: 'T-Shirts', slug: 'tshirt' },
    { name: 'Pantalons', slug: 'pantalon' },
    { name: 'Vestes', slug: 'veste' },
];

export default function Welcome({ categories = defaultCategories }) {
    return (
        <>
            <Head title="Accueil" />
            <ShopLayout>
                <section className="mb-10 rounded-2xl bg-slate-900 px-6 py-10 text-white sm:px-10">
                    <p className="mb-3 inline-flex rounded-full bg-white/10 px-3 py-1 text-xs uppercase tracking-wide">
                        Catalogue MVP
                    </p>
                    <h1 className="max-w-2xl text-3xl font-bold tracking-tight sm:text-4xl">
                        Trouvez rapidement vos produits avec une navigation simple
                        et des filtres clairs.
                    </h1>
                    <p className="mt-4 max-w-2xl text-slate-200">
                        Parcours principal: accueil vers categorie, puis fiche
                        produit.
                    </p>
                </section>

                <section>
                    <h2 className="mb-4 text-xl font-semibold">
                        Explorer par categorie
                    </h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {categories.map((category) => (
                            <a
                                key={category.slug}
                                href={`/categories/${category.slug}/products`}
                                className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300"
                            >
                                <p className="text-lg font-semibold text-slate-900">
                                    {category.name}
                                </p>
                                <p className="mt-2 text-sm text-slate-600">
                                    Voir les produits de la categorie.
                                </p>
                            </a>
                        ))}
                    </div>
                </section>
            </ShopLayout>
        </>
    );
}
