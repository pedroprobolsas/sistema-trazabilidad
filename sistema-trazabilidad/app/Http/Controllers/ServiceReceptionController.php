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
}