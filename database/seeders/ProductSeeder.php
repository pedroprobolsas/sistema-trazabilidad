<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'code' => 'PB-001',
                'name' => 'Bolsa Transparente 20x30',
                'description' => 'Bolsa transparente de polietileno de baja densidad',
                'unit_weight' => 0.0050,
            ],
            [
                'code' => 'PB-002',
                'name' => 'Bolsa Impresa 25x35',
                'description' => 'Bolsa impresa a 2 tintas de polietileno de alta densidad',
                'unit_weight' => 0.0075,
            ],
            [
                'code' => 'PB-003',
                'name' => 'Laminado Metalizado 30x40',
                'description' => 'Laminado metalizado para empaque de alimentos',
                'unit_weight' => 0.0100,
            ],
            [
                'code' => 'PB-004',
                'name' => 'Bolsa Biodegradable 15x25',
                'description' => 'Bolsa biodegradable para uso general',
                'unit_weight' => 0.0040,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}