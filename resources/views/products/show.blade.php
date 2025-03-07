@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $product->code }} - {{ $product->name }}</h1>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información del Producto</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Código:</th>
                            <td>{{ $product->code }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $product->description }}</td>
                        </tr>
                        <tr>
                            <th>Peso Unitario:</th>
                            <td>{{ number_format($product->unit_weight, 4) }} kg</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Órdenes Recientes</h5>
                    <a href="{{ route('service-orders.index', ['product_id' => $product->id]) }}" class="btn btn-sm btn-primary">
                        Ver todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->serviceOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('service-orders.show', $order) }}">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                        <td>{{ $order->provider->name }}</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">Pendiente</span>
                                            @else
                                                <span class="badge bg-success">Completado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No hay órdenes para este producto</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection