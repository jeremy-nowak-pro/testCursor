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
        Category::query()
            ->whereNotIn('slug', ['tshirt', 'pantalon', 'veste'])
            ->delete();

        $categories = [
            'tshirt' => Category::query()->updateOrCreate(
                ['slug' => 'tshirt'],
                ['name' => 'T-Shirts']
            ),
            'pantalon' => Category::query()->updateOrCreate(
                ['slug' => 'pantalon'],
                ['name' => 'Pantalons']
            ),
            'veste' => Category::query()->updateOrCreate(
                ['slug' => 'veste'],
                ['name' => 'Vestes']
            ),
        ];

        $products = [
            [
                'slug' => 'tshirt-basic-noir',
                'category_slug' => 'tshirt',
                'name' => 'T-Shirt Basic Noir',
                'description' => 'T-shirt noir pour usage quotidien.',
                'price' => 19.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Tshirt+Noir',
                'images' => ['https://placehold.co/1000x1000/png?text=Tshirt+Noir'],
                'type' => 'tshirt',
                'format' => 'standard',
                'in_stock' => true,
            ],
            [
                'slug' => 'tshirt-premium-blanc',
                'category_slug' => 'tshirt',
                'name' => 'T-Shirt Premium Blanc',
                'description' => 'T-shirt premium blanc coupe confortable.',
                'price' => 29.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Tshirt+Blanc',
                'images' => ['https://placehold.co/1000x1000/png?text=Tshirt+Blanc'],
                'type' => 'tshirt',
                'format' => 'premium',
                'in_stock' => true,
            ],
            [
                'slug' => 'pantalon-chino-beige',
                'category_slug' => 'pantalon',
                'name' => 'Pantalon Chino Beige',
                'description' => 'Chino polyvalent pour tenue casual.',
                'price' => 49.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Chino+Beige',
                'images' => ['https://placehold.co/1000x1000/png?text=Chino+Beige'],
                'type' => 'pantalon',
                'format' => 'standard',
                'in_stock' => true,
            ],
            [
                'slug' => 'pantalon-oversize-gris',
                'category_slug' => 'pantalon',
                'name' => 'Pantalon Oversize Gris',
                'description' => 'Coupe ample avec tissu souple.',
                'price' => 59.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Pantalon+Gris',
                'images' => ['https://placehold.co/1000x1000/png?text=Pantalon+Gris'],
                'type' => 'pantalon',
                'format' => 'oversize',
                'in_stock' => true,
            ],
            [
                'slug' => 'veste-denim-bleu',
                'category_slug' => 'veste',
                'name' => 'Veste Denim Bleu',
                'description' => 'Veste denim bleu style urbain.',
                'price' => 79.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Veste+Denim',
                'images' => ['https://placehold.co/1000x1000/png?text=Veste+Denim'],
                'type' => 'veste',
                'format' => 'standard',
                'in_stock' => true,
            ],
            [
                'slug' => 'veste-premium-cuir',
                'category_slug' => 'veste',
                'name' => 'Veste Premium Cuir',
                'description' => 'Veste premium en simili cuir.',
                'price' => 129.90,
                'currency' => 'EUR',
                'thumbnail' => 'https://placehold.co/400x400/png?text=Veste+Cuir',
                'images' => ['https://placehold.co/1000x1000/png?text=Veste+Cuir'],
                'type' => 'veste',
                'format' => 'premium',
                'in_stock' => true,
            ],
        ];

        foreach ($products as $payload) {
            Product::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                [
                    'category_id' => $categories[$payload['category_slug']]->id,
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                    'price' => $payload['price'],
                    'currency' => $payload['currency'],
                    'thumbnail' => $payload['thumbnail'],
                    'images' => $payload['images'],
                    'type' => $payload['type'],
                    'format' => $payload['format'],
                    'in_stock' => $payload['in_stock'],
                ]
            );
        }
    }
}
