<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::orderBy('name')->get();
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        return view('providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:20|unique:providers',
        ]);

        Provider::create($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Provider $provider)
    {
        $provider->load(['serviceOrders' => function($query) {
            $query->latest()->take(5);
        }]);
        
        return view('providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:20|unique:providers,tax_id,' . $provider->id,
        ]);

        $provider->update($validated);

        return redirect()->route('providers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Provider $provider)
    {
        try {
            $provider->delete();
            return redirect()->route('providers.index')
                ->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('providers.index')
                ->with('error', 'No se puede eliminar el proveedor porque tiene órdenes asociadas.');
        }
    }
}