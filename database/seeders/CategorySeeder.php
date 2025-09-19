<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Face Care', 'description' => 'Facial skincare products and treatments', 'slug' => 'face-care'],
            ['name' => 'Sun Protection', 'description' => 'Sunscreens and UV protection products', 'slug' => 'sun-protection'],
            ['name' => 'Anti-Aging', 'description' => 'Anti-aging creams and treatments', 'slug' => 'anti-aging'],
            ['name' => 'Exfoliation', 'description' => 'Exfoliating scrubs and treatments', 'slug' => 'exfoliation'],
            ['name' => 'Body Care', 'description' => 'Body lotions and skincare products', 'slug' => 'body-care'],
            ['name' => 'Eye Care', 'description' => 'Eye creams and treatments', 'slug' => 'eye-care'],
            ['name' => 'Serums & Treatments', 'description' => 'Specialized serums and treatment products', 'slug' => 'serums-treatments'],
            ['name' => 'Cleansers', 'description' => 'Facial cleansers and cleansing products', 'slug' => 'cleansers'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
