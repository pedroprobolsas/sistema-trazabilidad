<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run()
    {
        $serviceTypes = [
            [
                'name' => 'Impresión',
                'description' => 'Servicio de impresión de material',
            ],
            [
                'name' => 'Laminación',
                'description' => 'Servicio de laminación de material',
            ],
            [
                'name' => 'Corte',
                'description' => 'Servicio de corte de material',
            ],
            [
                'name' => 'Sellado',
                'description' => 'Servicio de sellado de material',
            ],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::create($serviceType);
        }
    }
}