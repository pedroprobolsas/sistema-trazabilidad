<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('name')->get();
        return view('service_types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('service_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:service_types',
            'description' => 'nullable|string|max:255',
        ]);

        ServiceType::create($request->all());

        return redirect()->route('service-types.index')
            ->with('success', 'Tipo de servicio creado correctamente.');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service_types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:service_types,name,' . $serviceType->id,
            'description' => 'nullable|string|max:255',
        ]);

        $serviceType->update($request->all());

        return redirect()->route('service-types.index')
            ->with('success', 'Tipo de servicio actualizado correctamente.');
    }

    public function destroy(ServiceType $serviceType)
    {
        // Check if the service type is being used
        if ($serviceType->serviceOrders()->count() > 0) {
            return redirect()->route('service-types.index')
                ->with('error', 'No se puede eliminar este tipo de servicio porque está siendo utilizado en órdenes de servicio.');
        }

        $serviceType->delete();

        return redirect()->route('service-types.index')
            ->with('success', 'Tipo de servicio eliminado correctamente.');
    }
}