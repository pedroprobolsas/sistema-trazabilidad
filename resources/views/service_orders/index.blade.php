@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Órdenes de Servicio</h1>
        <a href="{{ route('service-orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Orden
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Tipo de Servicio</th>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Compromiso</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->serviceType->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ $order->product->name }}</td>
                                <td>{{ $order->request_date }}</td>
                                <td>{{ $order->commitment_date }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @else
                                        <span class="badge bg-success">Completado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('service-orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('service-orders.print', $order) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if($order->status == 'pending')
                                            <a href="{{ route('service-receptions.create', $order) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Recibir
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay órdenes de servicio registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection