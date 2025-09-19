<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        // Create inventory records for each product in each warehouse
        foreach ($products as $product) {
            foreach ($warehouses as $warehouse) {
                // Random stock levels - some will be low stock
                $quantity = rand(0, 200);
                
                // Make some items low stock for demonstration
                if (rand(1, 4) == 1) { // 25% chance of low stock
                    $quantity = rand(0, $product->minimum_stock);
                }

                Inventory::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $quantity,
                    'reserved_quantity' => rand(0, min(5, $quantity)), // Small reserved amounts
                    'average_cost' => $product->cost_price + (rand(-200, 300) / 100), // Slight variation from product cost
                    'last_movement_at' => now()->subDays(rand(1, 30))
                ]);
            }
        }
    }
}