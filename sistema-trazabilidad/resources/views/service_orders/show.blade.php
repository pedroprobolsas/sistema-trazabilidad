@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Orden de Servicio: {{ $serviceOrder->order_number }}</h1>
        <div>
            <a href="{{ route('service-orders.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('service-orders.print', $serviceOrder) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> Imprimir
            </a>
            @if($serviceOrder->status == 'pending')
                <a href="{{ route('service-receptions.create', $serviceOrder) }}" class="btn btn-success ms-2">
                    <i class="fas fa-check"></i> Registrar Recepción
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Número de Orden:</strong></p>
                            <p>{{ $serviceOrder->order_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Estado:</strong></p>
                            <p>
                                @if($serviceOrder->status == 'pending')
                                    <span class="badge bg-warning">Pendiente</span>
                                @else
                                    <span class="badge bg-success">Completado</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tipo de Servicio:</strong></p>
                            <p>{{ $serviceOrder->serviceType->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Proveedor:</strong></p>
                            <p>{{ $serviceOrder->provider->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1"><strong>Producto:</strong></p>
                            <p>{{ $serviceOrder->product->code }} - {{ $serviceOrder->product->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Cantidad (kg):</strong></p>
                            <p>{{ number_format($serviceOrder->quantity_kg, 2) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Cantidad (unidades):</strong></p>
                            <p>{{ number_format($serviceOrder->quantity_units) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Costo:</strong></p>
                            <p>${{ number_format($serviceOrder->service_cost, 2) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fecha de Solicitud:</strong></p>
                            <p>{{ date('d/m/Y', strtotime($serviceOrder->request_date)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fecha de Compromiso:</strong></p>
                            <p>{{ date('d/m/Y', strtotime($serviceOrder->commitment_date)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información de Transporte</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Placa del Vehículo</th>
                            <td>{{ $serviceOrder->delivery_vehicle_plate }}</td>
                        </tr>
                        <tr>
                            <th>Conductor</th>
                            <td>{{ $serviceOrder->delivery_driver_name }}</td>
                        </tr>
                        <tr>
                            <th>Identificación</th>
                            <td>{{ $serviceOrder->delivery_driver_id }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>{{ $serviceOrder->delivery_driver_phone }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($serviceOrder->reception)
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Información de Recepción</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Fecha de Recepción</th>
                                <td>{{ date('d/m/Y', strtotime($serviceOrder->reception->reception_date)) }}</td>
                            </tr>
                            <tr>
                                <th>Cantidad Recibida (kg)</th>
                                <td>{{ number_format($serviceOrder->reception->received_quantity_kg, 2) }} kg</td>
                            </tr>
                            <tr>
                                <th>Cantidad Recibida (unidades)</th>
                                <td>{{ number_format($serviceOrder->reception->received_quantity_units) }} unidades</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
