<?php

namespace Tests\Feature;

use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_listing_returns_200_with_expected_inertia_payload(): void
    {
        $response = $this->get('/categories/tshirt/products');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalog/CategoryListing')
                ->where('category.slug', 'tshirt')
                ->where('category.name', 'T-Shirts')
                ->has('data.items', 2)
                ->where('meta.pagination.page', 1)
                ->where('meta.pagination.per_page', 12)
                ->where('meta.pagination.total', 2)
                ->where('selected_filters.min_price', null)
                ->where('selected_filters.max_price', null)
                ->where('selected_filters.type', null)
                ->where('selected_filters.format', null)
            );
    }

    public function test_listing_returns_404_for_unknown_category_slug(): void
    {
        $this->get('/categories/inconnue/products')->assertNotFound();
    }

    public function test_listing_returns_422_for_invalid_filters(): void
    {
        $response = $this->get('/categories/tshirt/products?min_price=-1&page=0&type=invalid');

        $response
            ->assertStatus(422)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalog/CategoryListing')
                ->has('errors.min_price')
                ->has('errors.page')
                ->has('errors.type')
            );
    }

    public function test_detail_returns_200_with_expected_inertia_payload(): void
    {
        $response = $this->get('/products/veste-denim-bleu');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Catalog/ProductShow')
                ->where('data.slug', 'veste-denim-bleu')
                ->where('data.name', 'Veste Denim Bleu')
                ->where('data.currency', 'EUR')
                ->where('data.type', 'veste')
                ->where('data.format', 'standard')
                ->where('data.in_stock', true)
            );
    }

    public function test_detail_returns_404_for_unknown_product_slug(): void
    {
        $this->get('/products/inconnu')->assertNotFound();
    }
}
