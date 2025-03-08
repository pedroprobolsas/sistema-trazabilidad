<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceType;
use App\Models\Provider;
use App\Models\Product;

class ReportsController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::all();
        $providers = Provider::all();
        $products = Product::all();

        return view('reports.index', compact('serviceTypes', 'providers', 'products'));
    }

    public function generate(Request $request)
    {
        // Aquí irá la lógica para generar los reportes
        // Por ahora retornamos a la vista con un mensaje
        return back()->with('message', 'Función en desarrollo');
    }
}