import { Link } from '@inertiajs/react';

const fallbackCategories = [
    { name: 'T-Shirts', slug: 'tshirt' },
    { name: 'Pantalons', slug: 'pantalon' },
    { name: 'Vestes', slug: 'veste' },
];

export default function NavCategories({ categories }) {
    const navCategories =
        Array.isArray(categories) && categories.length > 0
            ? categories
            : fallbackCategories;

    return (
        <nav className="flex flex-wrap items-center gap-3 text-sm">
            {navCategories.map((category) => (
                <Link
                    key={category.slug}
                    href={`/categories/${category.slug}/products`}
                    className="rounded px-2 py-1 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                >
                    {category.name}
                </Link>
            ))}
        </nav>
    );
}
