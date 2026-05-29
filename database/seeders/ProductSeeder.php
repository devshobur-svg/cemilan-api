<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Makaroni Pedas',
            'price' => 5000,
            'category' => 'Gorengan',
            'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=150',
            'has_variants' => true,
            'variants' => ["Level 1 (Asin)", "Level 3 (Sedang)", "Level 5 (Nangis)"],
            'stock' => 50
        ]);

        Product::create([
            'name' => 'Basreng Daun Jeruk',
            'price' => 8000,
            'category' => 'Cemilan Kering',
            'image' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=150',
            'has_variants' => true,
            'variants' => ["Original", "Pedas Daun Jeruk"],
            'stock' => 35
        ]);

        Product::create([
            'name' => 'Cimol Bojot',
            'price' => 6000,
            'category' => 'Gorengan',
            'image' => 'https://images.unsplash.com/photo-1566740933430-b5e70b06d2d5?w=150',
            'has_variants' => false,
            'variants' => [],
            'stock' => 20
        ]);

        Product::create([
            'name' => 'Es Teh Manis',
            'price' => 3000,
            'category' => 'Minuman',
            'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=150',
            'has_variants' => false,
            'variants' => [],
            'stock' => 100
        ]);
    }
}