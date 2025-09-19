<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Vitamin C Serum',
                'sku' => 'SKIN-001',
                'description' => 'Brightening vitamin C serum with hyaluronic acid',
                'category_id' => 1, // Face Care
                'supplier_id' => 1, // Beauty Essentials Co
                'cost_price' => 12.00,
                'selling_price' => 28.00,
                'unit' => 'bottle',
                'minimum_stock' => 30,
                'maximum_stock' => 200,
                'barcode' => '1234567890123',
                'location' => 'A1-B2'
            ],
            [
                'name' => 'Hydrating Moisturizer',
                'sku' => 'SKIN-002',
                'description' => 'Daily hydrating face moisturizer for all skin types',
                'category_id' => 1, // Face Care
                'supplier_id' => 2, // Glow Beauty Supply
                'cost_price' => 8.50,
                'selling_price' => 22.00,
                'unit' => 'tube',
                'minimum_stock' => 50,
                'maximum_stock' => 300,
                'barcode' => '1234567890124',
                'location' => 'C3-D4'
            ],
            [
                'name' => 'Gentle Cleanser',
                'sku' => 'SKIN-003',
                'description' => 'Foaming facial cleanser for sensitive skin',
                'category_id' => 1, // Face Care
                'supplier_id' => 3, // Pure Skin Solutions
                'cost_price' => 6.00,
                'selling_price' => 18.00,
                'unit' => 'bottle',
                'minimum_stock' => 40,
                'maximum_stock' => 250,
                'barcode' => '1234567890125',
                'location' => 'E5-F6'
            ],
            [
                'name' => 'SPF 50 Sunscreen',
                'sku' => 'SKIN-004',
                'description' => 'Broad spectrum sunscreen with UVA/UVB protection',
                'category_id' => 2, // Sun Protection
                'supplier_id' => 1, // Beauty Essentials Co
                'cost_price' => 10.00,
                'selling_price' => 25.00,
                'unit' => 'tube',
                'minimum_stock' => 35,
                'maximum_stock' => 180,
                'barcode' => '1234567890126',
                'location' => 'G7-H8'
            ],
            [
                'name' => 'Retinol Night Cream',
                'sku' => 'SKIN-005',
                'description' => 'Anti-aging retinol cream for overnight treatment',
                'category_id' => 3, // Anti-Aging
                'supplier_id' => 4, // Luxe Beauty Brands
                'cost_price' => 25.00,
                'selling_price' => 65.00,
                'unit' => 'jar',
                'minimum_stock' => 20,
                'maximum_stock' => 120,
                'barcode' => '1234567890127',
                'location' => 'I9-J10'
            ],
            [
                'name' => 'Exfoliating Scrub',
                'sku' => 'SKIN-006',
                'description' => 'Gentle exfoliating scrub with natural particles',
                'category_id' => 4, // Exfoliation
                'supplier_id' => 2, // Glow Beauty Supply
                'cost_price' => 7.50,
                'selling_price' => 20.00,
                'unit' => 'tube',
                'minimum_stock' => 25,
                'maximum_stock' => 150,
                'barcode' => '1234567890128',
                'location' => 'K11-L12'
            ],
            [
                'name' => 'Hyaluronic Acid Serum',
                'sku' => 'SKIN-007',
                'description' => 'Intensive hydrating serum with hyaluronic acid',
                'category_id' => 1, // Face Care
                'supplier_id' => 3, // Pure Skin Solutions
                'cost_price' => 15.00,
                'selling_price' => 35.00,
                'unit' => 'bottle',
                'minimum_stock' => 30,
                'maximum_stock' => 200,
                'barcode' => '1234567890129',
                'location' => 'M13-N14'
            ],
            [
                'name' => 'Eye Cream',
                'sku' => 'SKIN-008',
                'description' => 'Anti-aging eye cream for dark circles and puffiness',
                'category_id' => 3, // Anti-Aging
                'supplier_id' => 4, // Luxe Beauty Brands
                'cost_price' => 18.00,
                'selling_price' => 45.00,
                'unit' => 'tube',
                'minimum_stock' => 25,
                'maximum_stock' => 100,
                'barcode' => '1234567890130',
                'location' => 'O15-P16'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}