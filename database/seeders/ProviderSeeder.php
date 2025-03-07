<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run()
    {
        $providers = [
            [
                'name' => 'Impresiones Rápidas S.A.',
                'contact_name' => 'Juan Pérez',
                'phone' => '123-456-7890',
                'email' => 'juan@impresionesrapidas.com',
                'address' => 'Calle 123 #45-67, Ciudad',
                'tax_id' => '900123456-7',
            ],
            [
                'name' => 'Laminados Industriales Ltda.',
                'contact_name' => 'María Rodríguez',
                'phone' => '987-654-3210',
                'email' => 'maria@laminadosindustriales.com',
                'address' => 'Avenida 789 #12-34, Ciudad',
                'tax_id' => '900234567-8',
            ],
            [
                'name' => 'Cortes Precisos S.A.S.',
                'contact_name' => 'Carlos Gómez',
                'phone' => '456-789-0123',
                'email' => 'carlos@cortesprecisos.com',
                'address' => 'Carrera 456 #78-90, Ciudad',
                'tax_id' => '900345678-9',
            ],
        ];

        foreach ($providers as $provider) {
            Provider::create($provider);
        }
    }
}