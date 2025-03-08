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

    public function print(ServiceOrder $serviceOrder)
    {
        $pdf = PDF::loadView('service_orders.print', compact('serviceOrder'));
        return $pdf->download('service-order-'.$serviceOrder->order_number.'.pdf');
    }

    /**
     * API Methods
     */
    
    public function apiIndex()
    {
        $orders = ServiceOrder::with(['provider', 'product'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'provider_id' => $order->provider_id,
                    'provider_name' => $order->provider->name ?? 'N/A',
                    'product_id' => $order->product_id,
                    'product_name' => $order->product->name ?? 'N/A',
                    'quantity' => $order->quantity_kg,
                    'service_type' => $order->serviceType->name ?? 'N/A',
                    'request_date' => $order->request_date,
                    'due_date' => $order->commitment_date,
                    'status' => $order->status,
                    'notes' => $order->notes
                ];
            });
        
        return response()->json($orders);
    }
    
    public function apiShow(ServiceOrder $order)
    {
        $order->load(['provider', 'product', 'serviceType']);
        
        $data = [
            'id' => $order->id,
            'provider_id' => $order->provider_id,
            'provider_name' => $order->provider->name ?? 'N/A',
            'product_id' => $order->product_id,
            'product_name' => $order->product->name ?? 'N/A',
            'quantity' => $order->quantity_kg,
            'service_type' => $order->serviceType->name ?? 'N/A',
            'request_date' => $order->request_date,
            'due_date' => $order->commitment_date,
            'status' => $order->status,
            'notes' => $order->notes
        ];
        
        return response()->json($data);
    }
    
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'service_type' => 'required|string',
            'request_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:request_date',
            'notes' => 'nullable|string',
        ]);
        
        // Find service type ID based on name
        $serviceType = ServiceType::where('name', $validated['service_type'])->first();
        $serviceTypeId = $serviceType ? $serviceType->id : 1; // Default to 1 if not found
        
        $orderData = [
            'service_type_id' => $serviceTypeId,
            'provider_id' => $validated['provider_id'],
            'product_id' => $validated['product_id'],
            'quantity_kg' => $validated['quantity'],
            'quantity_units' => 0, // Default value
            'service_cost' => 0, // Default value
            'request_date' => $validated['request_date'],
            'commitment_date' => $validated['due_date'],
            'delivery_vehicle_plate' => 'N/A', // Default value
            'delivery_driver_name' => 'N/A', // Default value
            'delivery_driver_id' => 'N/A', // Default value
            'delivery_driver_phone' => 'N/A', // Default value
            'notes' => $validated['notes'] ?? '',
            'status' => 'pending',
            'delivered_by' => auth()->id() ?? 1,
            'received_by' => 1, // Default value
        ];
        
        $order = ServiceOrder::create($orderData);
        
        return response()->json($order, 201);
    }
    
    public function apiUpdate(Request $request, ServiceOrder $order)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'service_type' => 'required|string',
            'request_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:request_date',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        // Find service type ID based on name
        $serviceType = ServiceType::where('name', $validated['service_type'])->first();
        $serviceTypeId = $serviceType ? $serviceType->id : $order->service_type_id;
        
        $orderData = [
            'service_type_id' => $serviceTypeId,
            'provider_id' => $validated['provider_id'],
            'product_id' => $validated['product_id'],
            'quantity_kg' => $validated['quantity'],
            'request_date' => $validated['request_date'],
            'commitment_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? $order->notes,
            'status' => $validated['status'],
        ];
        
        $order->update($orderData);
        
        return response()->json($order);
    }
    
    public function apiDestroy(ServiceOrder $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
