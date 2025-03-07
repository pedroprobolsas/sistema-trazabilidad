@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Nueva Orden de Servicio</h1>
        <a href="{{ route('service-orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('service-orders.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="service_type_id" class="form-label">Tipo de Servicio</label>
                            <select name="service_type_id" id="service_type_id" class="form-select @error('service_type_id') is-invalid @enderror" required>
                                <option value="">Seleccione un tipo de servicio</option>
                                @foreach($serviceTypes as $serviceType)
                                    <option value="{{ $serviceType->id }}" {{ old('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                        {{ $serviceType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="provider_id" class="form-label">Proveedor</label>
                            <select name="provider_id" id="provider_id" class="form-select @error('provider_id') is-invalid @enderror" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}" {{ old('provider_id') == $provider->id ? 'selected' : '' }}>
                                        {{ $provider->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provider_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_id" class="form-label">Producto</label>
                            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Seleccione un producto</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->code }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="request_date" class="form-label">Fecha de Solicitud</label>
                            <input type="date" name="request_date" id="request_date" class="form-control @error('request_date') is-invalid @enderror" value="{{ old('request_date', date('Y-m-d')) }}" required>
                            @error('request_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commitment_date" class="form-label">Fecha de Compromiso</label>
                            <input type="date" name="commitment_date" id="commitment_date" class="form-control @error('commitment_date') is-invalid @enderror" value="{{ old('commitment_date') }}" required>
                            @error('commitment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="quantity_kg" class="form-label">Cantidad (kg)</label>
                            <input type="number" step="0.01" name="quantity_kg" id="quantity_kg" class="form-control @error('quantity_kg') is-invalid @enderror" value="{{ old('quantity_kg') }}" required>
                            @error('quantity_kg')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="quantity_units" class="form-label">Cantidad (unidades)</label>
                            <input type="number" name="quantity_units" id="quantity_units" class="form-control @error('quantity_units') is-invalid @enderror" value="{{ old('quantity_units') }}" required>
                            @error('quantity_units')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="service_cost" class="form-label">Costo del Servicio</label>
                            <input type="number" step="0.01" name="service_cost" id="service_cost" class="form-control @error('service_cost') is-invalid @enderror" value="{{ old('service_cost') }}" required>
                            @error('service_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <h4 class="mt-4 mb-3">Información de Transporte</h4>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="delivery_vehicle_plate" class="form-label">Placa del Vehículo</label>
                            <input type="text" name="delivery_vehicle_plate" id="delivery_vehicle_plate" class="form-control @error('delivery_vehicle_plate') is-invalid @enderror" value="{{ old('delivery_vehicle_plate') }}" required>
                            @error('delivery_vehicle_plate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="delivery_driver_name" class="form-label">Nombre del Conductor</label>
                            <input type="text" name="delivery_driver_name" id="delivery_driver_name" class="form-control @error('delivery_driver_name') is-invalid @enderror" value="{{ old('delivery_driver_name') }}" required>
                            @error('delivery_driver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="delivery_driver_id" class="form-label">Identificación del Conductor</label>
                            <input type="text" name="delivery_driver_id" id="delivery_driver_id" class="form-control @error('delivery_driver_id') is-invalid @enderror" value="{{ old('delivery_driver_id') }}" required>
                            @error('delivery_driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="delivery_driver_phone" class="form-label">Teléfono del Conductor</label>
                            <input type="text" name="delivery_driver_phone" id="delivery_driver_phone" class="form-control @error('delivery_driver_phone') is-invalid @enderror" value="{{ old('delivery_driver_phone') }}" required>
                            @error('delivery_driver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="received_by" class="form-label">Recibido por (Proveedor)</label>
                            <input type="text" name="received_by" id="received_by" class="form-control @error('received_by') is-invalid @enderror" value="{{ old('received_by') }}" required>
                            @error('received_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
