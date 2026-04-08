<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CatalogModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_run_without_error(): void
    {
        $this->assertTrue(Schema::hasTable('categories'));
        $this->assertTrue(Schema::hasTable('products'));
    }

    public function test_category_has_many_products(): void
    {
        $category = Category::factory()
            ->has(Product::factory()->count(2))
            ->create();

        $this->assertCount(2, $category->products);
        $this->assertTrue($category->products->every(fn (Product $p) => $p->category_id === $category->id));
    }

    public function test_product_belongs_to_category(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertSame($product->category_id, $product->category->id);
    }

    public function test_catalog_seeder_creates_categories_and_products(): void
    {
        $this->seed(CatalogSeeder::class);

        $this->assertDatabaseHas('categories', ['slug' => 'tshirt']);
        $this->assertDatabaseHas('categories', ['slug' => 'pantalon']);
        $this->assertDatabaseHas('categories', ['slug' => 'veste']);
        $this->assertDatabaseHas('products', ['slug' => 'tshirt-basic-noir']);
        $this->assertDatabaseHas('products', ['slug' => 'pantalon-chino-beige']);
        $this->assertDatabaseHas('products', ['slug' => 'veste-denim-bleu']);

        $tshirts = Category::query()->where('slug', 'tshirt')->firstOrFail();
        $this->assertGreaterThanOrEqual(2, $tshirts->products()->count());
    }

    public function test_category_validation_rules_pass_for_valid_data(): void
    {
        $validator = Validator::make(
            ['name' => 'Gadgets', 'slug' => 'gadgets'],
            Category::validationRules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_product_validation_rules_pass_for_valid_data(): void
    {
        $category = Category::factory()->create();

        $validator = Validator::make(
            [
                'category_id' => $category->id,
                'name' => 'Widget',
                'slug' => 'widget',
                'description' => 'A widget.',
                'price' => 12.5,
                'currency' => 'EUR',
                'thumbnail' => 'https://example.test/t.jpg',
                'images' => ['https://example.test/a.jpg'],
                'type' => 'physical',
                'format' => 'standard',
                'in_stock' => true,
            ],
            Product::validationRules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_product_payload_maps_to_contract_field_shapes(): void
    {
        $product = Product::factory()->create([
            'currency' => 'EUR',
            'images' => ['https://example.test/1.jpg', 'https://example.test/2.jpg'],
        ]);

        $this->assertIsInt($product->id);
        $this->assertIsString($product->slug);
        $this->assertIsString($product->name);
        $this->assertIsString($product->description ?? '');
        $this->assertIsString((string) $product->price);
        $this->assertSame('EUR', $product->currency);
        $this->assertIsArray($product->images);
        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertIsString($product->type);
        $this->assertIsString($product->format);
        $this->assertIsBool($product->in_stock);
        $this->assertTrue($product->thumbnail === null || is_string($product->thumbnail));
    }
}
