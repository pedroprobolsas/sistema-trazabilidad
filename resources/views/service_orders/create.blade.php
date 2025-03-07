@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Registrar Recepción de Servicio</h1>
        <a href="{{ route('service-orders.show', $serviceOrder) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información de la Orden de Servicio: {{ $serviceOrder->order_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Tipo de Servicio:</strong></p>
                            <p>{{ $serviceOrder->serviceType->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Proveedor:</strong></p>
                            <p>{{ $serviceOrder->provider->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Producto:</strong></p>
                            <p>{{ $serviceOrder->product->code }} - {{ $serviceOrder->product->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Cantidad Enviada:</strong></p>
                            <p>{{ number_format($serviceOrder->quantity_kg, 2) }} kg / {{ number_format($serviceOrder->quantity_units) }} unidades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('service-receptions.store', $serviceOrder) }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="reception_date" class="form-label">Fecha de Recepción</label>
                        <input type="date" name="reception_date" id="reception_date" class="form-control @error('reception_date') is-invalid @enderror" value="{{ old('reception_date', date('Y-m-d')) }}" required>
                        @error('reception_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="received_quantity_kg" class="form-label">Cantidad Recibida (kg)</label>
                        <input type="number" step="0.01" name="received_quantity_kg" id="received_quantity_kg" class="form-control @error('received_quantity_kg') is-invalid @enderror" value="{{ old('received_quantity_kg') }}" required>
                        @error('received_quantity_kg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="received_quantity_units" class="form-label">Cantidad Recibida (unidades)</label>
                        <input type="number" name="received_quantity_units" id="received_quantity_units" class="form-control @error('received_quantity_units') is-invalid @enderror" value="{{ old('received_quantity_units') }}" required>
                        @error('received_quantity_units')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="scrap_quantity_kg" class="form-label">Retales/Sobrantes (kg)</label>
                        <input type="number" step="0.01" name="scrap_quantity_kg" id="scrap_quantity_kg" class="form-control @error('scrap_quantity_kg') is-invalid @enderror" value="{{ old('scrap_quantity_kg', 0) }}" required>
                        @error('scrap_quantity_kg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="scrap_quantity_units" class="form-label">Retales/Sobrantes (unidades)</label>
                        <input type="number" name="scrap_quantity_units" id="scrap_quantity_units" class="form-control @error('scrap_quantity_units') is-invalid @enderror" value="{{ old('scrap_quantity_units', 0) }}" required>
                        @error('scrap_quantity_units')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h4 class="mt-4 mb-3">Información de Transporte</h4>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="pickup_vehicle_plate" class="form-label">Placa del Vehículo</label>
                        <input type="text" name="pickup_vehicle_plate" id="pickup_vehicle_plate" class="form-control @error('pickup_vehicle_plate') is-invalid @enderror" value="{{ old('pickup_vehicle_plate') }}" required>
                        @error('pickup_vehicle_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="pickup_driver_name" class="form-label">Nombre del Conductor</label>
                        <input type="text" name="pickup_driver_name" id="pickup_driver_name" class="form-control @error('pickup_driver_name') is-invalid @enderror" value="{{ old('pickup_driver_name') }}" required>
                        @error('pickup_driver_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="pickup_driver_id" class="form-label">Identificación del Conductor</label>
                        <input type="text" name="pickup_driver_id" id="pickup_driver_id" class="form-control @error('pickup_driver_id') is-invalid @enderror" value="{{ old('pickup_driver_id') }}" required>
                        @error('pickup_driver_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h4 class="mt-4 mb-3">Responsables</h4>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivered_by" class="form-label">Entregado por (Proveedor)</label>
                        <input type="text" name="delivered_by" id="delivered_by" class="form-control @error('delivered_by') is-invalid @enderror" value="{{ old('delivered_by') }}" required>
                        @error('delivered_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Registrar Recepción
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection