<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Provider;
use App\Models\Product;
use Illuminate\Http\Request;
use PDF;

class ServiceOrderController extends Controller
{
    public function index()
    {
        $orders = ServiceOrder::with(['serviceType', 'provider', 'product'])->get();
        return view('service_orders.index', compact('orders'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::all();
        $providers = Provider::all();
        $products = Product::all();
        return view('service_orders.create', compact('serviceTypes', 'providers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'provider_id' => 'required|exists:providers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_kg' => 'required|numeric|min:0',
            'quantity_units' => 'required|integer|min:0',
            'service_cost' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'commitment_date' => 'required|date|after_or_equal:request_date',
            'delivery_vehicle_plate' => 'required|string',
            'delivery_driver_name' => 'required|string',
            'delivery_driver_id' => 'required|string',
            'delivery_driver_phone' => 'required|string',
        ]);

        $validated['delivered_by'] = auth()->id();
        $validated['received_by'] = $request->received_by;
        $validated['status'] = 'pending';

        $order = ServiceOrder::create($validated);

        return redirect()->route('service-orders.show', $order)->with('success', 'Service order created successfully');
    }

    public function show(ServiceOrder $serviceOrder)
    {
        return view('service_orders.show', compact('serviceOrder'));
    }

    public function printOrder(ServiceOrder $serviceOrder)
    {
        $pdf = PDF::loadView('service_orders.print', compact('serviceOrder'));
        return $pdf->download('service-order-'.$serviceOrder->order_number.'.pdf');
    }
}