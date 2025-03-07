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
                    <h5 class="mb-0">Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Número de Orden:</strong></p>
                            <p>{{ $serviceOrder->order_number }}</p>
                        </div>
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
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Cantidad Enviada (kg):</strong></p>
                            <p>{{ number_format($serviceOrder->quantity_kg, 2) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Cantidad Enviada (unidades):</strong></p>
                            <p>{{ number_format($serviceOrder->quantity_units) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Fecha de Solicitud:</strong></p>
                            <p>{{ date('d/m/Y', strtotime($serviceOrder->request_date)) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Fecha de Compromiso:</strong></p>
                            <p>{{ date('d/m/Y', strtotime($serviceOrder->commitment_date)) }}</p>
                        </div>
                    </div>
                </div>