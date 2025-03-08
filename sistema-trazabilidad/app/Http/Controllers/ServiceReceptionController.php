<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceReception;
use Illuminate\Http\Request;

class ServiceReceptionController extends Controller
{
    public function create(ServiceOrder $serviceOrder)
    {
        return view('service_receptions.create', compact('serviceOrder'));
    }

    public function store(Request $request, ServiceOrder $serviceOrder)
    {
        $validated = $request->validate([
            'reception_date' => 'required|date',
            'received_quantity_kg' => 'required|numeric|min:0',
            'received_quantity_units' => 'required|integer|min:0',
            'scrap_quantity_kg' => 'required|numeric|min:0',
            'scrap_quantity_units' => 'required|integer|min:0',
            'pickup_vehicle_plate' => 'required|string',
            'pickup_driver_name' => 'required|string',
            'pickup_driver_id' => 'required|string',
        ]);

        $validated['service_order_id'] = $serviceOrder->id;
        $validated['received_by'] = auth()->id();
        $validated['delivered_by'] = $request->delivered_by;

        ServiceReception::create($validated);
        
        $serviceOrder->update(['status' => 'completed']);

        return redirect()->route('service-orders.show', $serviceOrder)->with('success', 'Service reception recorded successfully');
    }

    /**
     * API Methods
     */
    
    public function apiIndex()
    {
        $receptions = ServiceReception::with(['serviceOrder.provider', 'serviceOrder.product'])
            ->get()
            ->map(function ($reception) {
                return [
                    'id' => $reception->id,
                    'service_order_number' => $reception->serviceOrder ? 'OS-' . str_pad($reception->serviceOrder->id, 4, '0', STR_PAD_LEFT) : 'N/A',
                    'provider_name' => $reception->serviceOrder->provider->name ?? 'N/A',
                    'product_name' => $reception->serviceOrder->product->name ?? 'N/A',
                    'received_quantity_kg' => $reception->received_quantity_kg,
                    'reception_date' => $reception->reception_date,
                    'quality' => $reception->quality ?? 'N/A',
                    'status' => $reception->status ?? 'Recibido',
                    'notes' => $reception->notes
                ];
            });
        
        return response()->json($receptions);
    }
    
    public function apiStore(Request $request)
    {
        // Log the request data for debugging
        \Log::info('Reception API Request Data:', $request->all());
        
        try {
            $validated = $request->validate([
                'service_order_id' => 'required|exists:service_orders,id',
                'reception_date' => 'required|date',
                'received_quantity_kg' => 'required|numeric|min:0',
                'status' => 'required|string',
                'notes' => 'nullable|string',
                'quality' => 'nullable|string',
                // Añadir campos faltantes con valores predeterminados
                'received_quantity_units' => 'nullable|integer',
                'scrap_quantity_kg' => 'nullable|numeric',
                'scrap_quantity_units' => 'nullable|integer',
                'pickup_vehicle_plate' => 'nullable|string',
                'pickup_driver_name' => 'nullable|string',
                'pickup_driver_id' => 'nullable|string',
            ]);
            
            // Asignar valores predeterminados a campos obligatorios que no vienen del formulario
            $validated['received_quantity_units'] = $validated['received_quantity_units'] ?? 0;
            $validated['scrap_quantity_kg'] = $validated['scrap_quantity_kg'] ?? 0;
            $validated['scrap_quantity_units'] = $validated['scrap_quantity_units'] ?? 0;
            $validated['pickup_vehicle_plate'] = $validated['pickup_vehicle_plate'] ?? 'N/A';
            $validated['pickup_driver_name'] = $validated['pickup_driver_name'] ?? 'N/A';
            $validated['pickup_driver_id'] = $validated['pickup_driver_id'] ?? 'N/A';
            
            $validated['received_by'] = auth()->id() ?? 1;
            $validated['delivered_by'] = $request->delivered_by ?? 1;
            
            // Log the validated data
            \Log::info('Reception Validated Data:', $validated);
            
            $reception = ServiceReception::create($validated);
            
            // Log the created reception
            \Log::info('Reception Created:', $reception->toArray());
            
            // Actualizar el estado de la orden de servicio
            $serviceOrder = ServiceOrder::find($validated['service_order_id']);
            if ($serviceOrder) {
                $serviceOrder->update(['status' => 'completed']);
                \Log::info('Service Order Updated:', ['id' => $serviceOrder->id, 'status' => 'completed']);
            }
            
            return response()->json($reception, 201);
        } catch (\Exception $e) {
            // Log any exceptions
            \Log::error('Reception API Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function apiShow(ServiceReception $reception)
    {
        $reception->load(['serviceOrder.provider', 'serviceOrder.product']);
        
        $data = [
            'id' => $reception->id,
            'service_order_number' => $reception->serviceOrder ? 'OS-' . str_pad($reception->serviceOrder->id, 4, '0', STR_PAD_LEFT) : 'N/A',
            'provider_name' => $reception->serviceOrder->provider->name ?? 'N/A',
            'product_name' => $reception->serviceOrder->product->name ?? 'N/A',
            'received_quantity_kg' => $reception->received_quantity_kg,
            'reception_date' => $reception->reception_date,
            'quality' => $reception->quality ?? 'N/A',
            'status' => $reception->status ?? 'Recibido',
            'notes' => $reception->notes
        ];
        
        return response()->json($data);
    }
    
    public function apiDestroy(ServiceReception $reception)
    {
        $reception->delete();
        return response()->json(['message' => 'Reception deleted successfully']);
    }
}
