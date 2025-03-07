<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Provider;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceOrdersExport;

class ReportController extends Controller
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
        $query = ServiceOrder::with(['reception', 'product', 'provider', 'serviceType']);
        
        if ($request->has('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }
        
        if ($request->has('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }
        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('request_date', [$request->start_date, $request->end_date]);
        }
        
        $orders = $query->get();
        
        $efficiency = $orders->map(function($order) {
            if ($order->reception) {
                $input = $order->reception->received_quantity_kg;
                $output = $order->quantity_kg;
                return [
                    'order' => $order,
                    'efficiency' => $output > 0 ? ($input / $output) * 100 : 0
                ];
            }
            return [
                'order' => $order,
                'efficiency' => 0
            ];
        });
        
        if ($request->has('export') && $request->export == 'excel') {
            return Excel::download(new ServiceOrdersExport($orders), 'service-orders-report.xlsx');
        }
        
        return view('reports.show', compact('orders', 'efficiency'));
    }
}