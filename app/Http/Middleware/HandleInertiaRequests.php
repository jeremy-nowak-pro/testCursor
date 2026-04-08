<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $categories = [
            ['name' => 'T-Shirts', 'slug' => 'tshirt'],
            ['name' => 'Pantalons', 'slug' => 'pantalon'],
            ['name' => 'Vestes', 'slug' => 'veste'],
        ];

        if (Schema::hasTable('categories')) {
            $categoriesFromDb = Category::query()
                ->whereIn('slug', ['tshirt', 'pantalon', 'veste'])
                ->orderByRaw("CASE slug WHEN 'tshirt' THEN 1 WHEN 'pantalon' THEN 2 WHEN 'veste' THEN 3 ELSE 4 END")
                ->get(['name', 'slug'])
                ->map(fn (Category $category) => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])
                ->values()
                ->all();

            if (! empty($categoriesFromDb)) {
                $categories = $categoriesFromDb;
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'categories' => $categories,
        ];
    }
}
