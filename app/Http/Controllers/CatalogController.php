<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CatalogController extends Controller
{
    public function categoryListing(Request $request, string $slug): InertiaResponse|HttpResponse
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $rules = [
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'type' => ['nullable', 'string', 'in:tshirt,pantalon,veste'],
            'format' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];

        $validator = Validator::make($request->query(), $rules);

        if ($validator->fails()) {
            return Inertia::render('Catalog/CategoryListing', [
                'data' => ['items' => []],
                'meta' => [
                    'pagination' => [
                        'page' => 1,
                        'per_page' => 12,
                        'total' => 0,
                    ],
                    'available_filters' => $this->availableFilters($category),
                ],
                'selected_filters' => $this->selectedFilters($request),
                'category' => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'errors' => $validator->errors()->toArray(),
            ])->toResponse($request)->setStatusCode(422);
        }

        $validated = $validator->validated();
        $perPage = 12;

        $productsQuery = $category->products()->newQuery();

        $productsQuery
            ->when(isset($validated['min_price']), fn ($q) => $q->where('price', '>=', $validated['min_price']))
            ->when(isset($validated['max_price']), fn ($q) => $q->where('price', '<=', $validated['max_price']))
            ->when(isset($validated['type']), fn ($q) => $q->where('type', $validated['type']))
            ->when(isset($validated['format']), fn ($q) => $q->where('format', $validated['format']));

        $page = (int) ($validated['page'] ?? 1);
        $paginator = $productsQuery->orderBy('id')->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('Catalog/CategoryListing', [
            'data' => [
                'items' => collect($paginator->items())->map(function (Product $product): array {
                    return [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'currency' => $product->currency,
                        'thumbnail' => $product->thumbnail,
                        'type' => $product->type,
                        'format' => $product->format,
                    ];
                })->values(),
            ],
            'meta' => [
                'pagination' => [
                    'page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
                'available_filters' => $this->availableFilters($category),
            ],
            'selected_filters' => $this->selectedFilters($request),
            'category' => [
                'name' => $category->name,
                'slug' => $category->slug,
            ],
        ]);
    }

    public function productShow(string $slug): InertiaResponse
    {
        $product = Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Catalog/ProductShow', [
            'data' => [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'currency' => $product->currency,
                'images' => $product->images ?? [],
                'category' => $product->category->name,
                'type' => $product->type,
                'format' => $product->format,
                'in_stock' => $product->in_stock,
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function availableFilters(Category $category): array
    {
        $prices = $category->products()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        $types = $category->products()->select('type')->distinct()->orderBy('type')->pluck('type')->values();
        $formats = $category->products()->select('format')->distinct()->orderBy('format')->pluck('format')->values();

        return [
            'price' => [
                'min' => $prices?->min_price !== null ? (float) $prices->min_price : null,
                'max' => $prices?->max_price !== null ? (float) $prices->max_price : null,
            ],
            'type' => $types,
            'format' => $formats,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function selectedFilters(Request $request): array
    {
        return [
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'type' => $request->query('type'),
            'format' => $request->query('format'),
        ];
    }
}
