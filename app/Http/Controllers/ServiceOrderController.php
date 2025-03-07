<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Provider;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['serviceType', 'provider', 'product']);
        
        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('service_type_id') && $request->service_type_id) {
            $query->where('service_type_id', $request->service_type_id);
        }
        
        if ($request->has('provider_id') && $request->provider_id) {
            $query->where('provider_id', $request->provider_id);
        }
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
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
        $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'provider_id' => 'required|exists:providers,id',
            'product_id' => 'required|exists:products,id',
            'quantity_kg' => 'required|numeric|min:0',
            'quantity_units' => 'required|integer|min:0',
            'service_cost' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'commitment_date' => 'required|date|after_or_equal:request_date',
            'delivery_vehicle_plate' => 'required|string|max:10',
            'delivery_driver_name' => 'required|string|max:100',
            'delivery_driver_id' => 'required|string|max:20',
            'delivery_driver_phone' => 'required|string|max:20',
            'received_by' => 'required|string|max:100',
        ]);
        
        // Generate order number
        $lastOrder = ServiceOrder::orderBy('id', 'desc')->first();
        $orderNumber = 'OS-' . (($lastOrder ? intval(substr($lastOrder->order_number, 3)) : 1000) + 1);
        
        $serviceOrder = new ServiceOrder();
        $serviceOrder->order_number = $orderNumber;
        $serviceOrder->service_type_id = $request->service_type_id;
        $serviceOrder->provider_id = $request->provider_id;
        $serviceOrder->product_id = $request->product_id;
        $serviceOrder->quantity_kg = $request->quantity_kg;
        $serviceOrder->quantity_units = $request->quantity_units;
        $serviceOrder->service_cost = $request->service_cost;
        $serviceOrder->request_date = $request->request_date;
        $serviceOrder->commitment_date = $request->commitment_date;
        $serviceOrder->delivery_vehicle_plate = $request->delivery_vehicle_plate;
        $serviceOrder->delivery_driver_name = $request->delivery_driver_name;
        $serviceOrder->delivery_driver_id = $request->delivery_driver_id;
        $serviceOrder->delivery_driver_phone = $request->delivery_driver_phone;
        $serviceOrder->delivered_by = Auth::id();
        $serviceOrder->received_by = $request->received_by;
        $serviceOrder->status = 'pending';
        $serviceOrder->save();
        
        return redirect()->route('service-orders.show', $serviceOrder)
            ->with('success', 'Orden de servicio creada correctamente.');
    }

    public function show(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['serviceType', 'provider', 'product', 'deliveredByUser', 'reception']);
        
        return view('service_orders.show', compact('serviceOrder'));
    }

    public function printOrder(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['serviceType', 'provider', 'product', 'deliveredByUser']);
        
        return view('service_orders.print', compact('serviceOrder'));
    }
}