<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Beauty Essentials Co',
                'contact_person' => 'Emma Rodriguez',
                'email' => 'emma@beautyessentials.com',
                'phone' => '+1-555-0123',
                'address' => '123 Beauty Lane',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'postal_code' => '90210',
                'notes' => 'Premium skincare and beauty products'
            ],
            [
                'name' => 'Glow Beauty Supply',
                'contact_person' => 'Sophia Chen',
                'email' => 'sophia@glowbeauty.com',
                'phone' => '+1-555-0456',
                'address' => '456 Glow Street',
                'city' => 'New York',
                'country' => 'USA',
                'postal_code' => '10001',
                'notes' => 'Natural and organic skincare products'
            ],
            [
                'name' => 'Pure Skin Solutions',
                'contact_person' => 'Isabella Martinez',
                'email' => 'isabella@pureskin.com',
                'phone' => '+1-555-0789',
                'address' => '789 Pure Avenue',
                'city' => 'Miami',
                'country' => 'USA',
                'postal_code' => '33101',
                'notes' => 'Clinical-grade skincare formulations'
            ],
            [
                'name' => 'Luxe Beauty Brands',
                'contact_person' => 'Olivia Thompson',
                'email' => 'olivia@luxebeauty.com',
                'phone' => '+1-555-0321',
                'address' => '321 Luxury Blvd',
                'city' => 'Beverly Hills',
                'country' => 'USA',
                'postal_code' => '90210',
                'notes' => 'High-end luxury skincare and cosmetics'
            ],
            [
                'name' => 'Natural Beauty Co',
                'contact_person' => 'Ava Williams',
                'email' => 'ava@naturalbeauty.com',
                'phone' => '+1-555-0654',
                'address' => '654 Natural Way',
                'city' => 'Portland',
                'country' => 'USA',
                'postal_code' => '97201',
                'notes' => 'Eco-friendly and sustainable beauty products'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
