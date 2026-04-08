<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Seed minimal catalogue data for local development and contract alignment checks.
     */
    public function run(): void
    {
        $books = Category::query()->firstOrCreate(
            ['slug' => 'books'],
            ['name' => 'Books']
        );

        $electronics = Category::query()->firstOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics']
        );

        Product::query()->firstOrCreate(
            ['slug' => 'sample-paperback-a'],
            [
                'category_id' => $books->id,
                'name' => 'Sample Paperback A',
                'description' => 'A sample book for listing and detail pages.',
                'price' => 19.99,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Book+A',
                'images' => [
                    'https://placehold.co/800x800/png?text=Book+A-1',
                    'https://placehold.co/800x800/png?text=Book+A-2',
                ],
                'type' => 'physical',
                'format' => 'standard',
                'in_stock' => true,
            ]
        );

        Product::query()->firstOrCreate(
            ['slug' => 'sample-ebook-b'],
            [
                'category_id' => $books->id,
                'name' => 'Sample E-book B',
                'description' => 'Digital edition for filter testing.',
                'price' => 9.99,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Ebook+B',
                'images' => [
                    'https://placehold.co/800x800/png?text=Ebook+B',
                ],
                'type' => 'digital',
                'format' => 'compact',
                'in_stock' => true,
            ]
        );

        Product::query()->firstOrCreate(
            ['slug' => 'sample-gadget-c'],
            [
                'category_id' => $electronics->id,
                'name' => 'Sample Gadget C',
                'description' => 'Electronics sample with distinct type/format.',
                'price' => 129.00,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Gadget+C',
                'images' => [
                    'https://placehold.co/800x800/png?text=Gadget+C-1',
                    'https://placehold.co/800x800/png?text=Gadget+C-2',
                ],
                'type' => 'physical',
                'format' => 'large',
                'in_stock' => true,
            ]
        );
    }
}
