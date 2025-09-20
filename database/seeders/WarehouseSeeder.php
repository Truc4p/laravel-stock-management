<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Central Warehouse',
                'code' => 'BCW001',
                'description' => 'Main skincare and beauty products storage facility',
                'address' => '1000 Beauty Boulevard',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'postal_code' => '90210',
                'manager_name' => 'Grace Wilson',
                'contact_phone' => '+1-555-1000',
                'contact_email' => 'grace@beautycentral.com'
            ],
            [
                'name' => 'Glow Distribution Center',
                'code' => 'GDC002',
                'description' => 'Premium skincare products distribution center',
                'address' => '2000 Glow Avenue',
                'city' => 'Miami',
                'country' => 'USA',
                'postal_code' => '33101',
                'manager_name' => 'Luna Rodriguez',
                'contact_phone' => '+1-555-2000',
                'contact_email' => 'luna@glowdistribution.com'
            ],
            [
                'name' => 'Pure Beauty Hub',
                'code' => 'PBH003',
                'description' => 'Natural and organic beauty products hub',
                'address' => '3000 Pure Street',
                'city' => 'Portland',
                'country' => 'USA',
                'postal_code' => '97201',
                'manager_name' => 'Zoe Chen',
                'contact_phone' => '+1-555-3000',
                'contact_email' => 'zoe@purebeauty.com'
            ]
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}