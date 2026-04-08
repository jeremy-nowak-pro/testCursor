import { Link, usePage } from '@inertiajs/react';

const fallbackCategories = [
    { name: 'Affiches', slug: 'affiches' },
    { name: 'Stickers', slug: 'stickers' },
    { name: 'Textiles', slug: 'textiles' },
];

export default function ShopLayout({ title, children }) {
    const { auth, categories } = usePage().props;
    const navCategories =
        Array.isArray(categories) && categories.length > 0
            ? categories
            : fallbackCategories;

    return (
        <div className="min-h-screen bg-slate-50 text-slate-900">
            <header className="border-b border-slate-200 bg-white">
                <div className="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6">
                    <Link href="/" className="text-xl font-bold tracking-tight">
                        Catalog MVP
                    </Link>

                    <nav className="flex flex-wrap items-center gap-3 text-sm">
                        {navCategories.map((category) => (
                            <a
                                key={category.slug}
                                href={`/categories/${category.slug}/products`}
                                className="rounded px-2 py-1 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                            >
                                {category.name}
                            </a>
                        ))}
                    </nav>

                    <div className="flex items-center gap-2 text-sm">
                        {auth?.user ? (
                            <Link
                                href={route('dashboard')}
                                className="rounded border border-slate-300 px-3 py-1.5 hover:bg-slate-100"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="rounded border border-slate-300 px-3 py-1.5 hover:bg-slate-100"
                                >
                                    Login
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="rounded bg-slate-900 px-3 py-1.5 font-medium text-white hover:bg-slate-800"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </header>

            <main className="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
                {title ? (
                    <h1 className="mb-6 text-2xl font-semibold tracking-tight">
                        {title}
                    </h1>
                ) : null}
                {children}
            </main>

            <footer className="border-t border-slate-200 bg-white">
                <div className="mx-auto flex w-full max-w-6xl flex-col justify-between gap-3 px-4 py-6 text-sm text-slate-600 sm:flex-row sm:px-6">
                    <p>© {new Date().getFullYear()} Catalog MVP</p>
                    <div className="flex flex-wrap gap-4">
                        <a href="/legal/mentions-legales" className="hover:text-slate-900">
                            Mentions legales
                        </a>
                        <a href="/legal/politique-cookies" className="hover:text-slate-900">
                            Politique cookies
                        </a>
                        <a href="/legal/confidentialite" className="hover:text-slate-900">
                            Confidentialite
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    );
}
