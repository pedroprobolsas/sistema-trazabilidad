@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(isset($efficiency))
                <!-- Efficiency Report -->
                <h5 class="mb-3">Reporte de Eficiencia</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Producto</th>
                                <th>Enviado (kg)</th>
                                <th>Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($efficiency as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('service-orders.show', $item['order']) }}">
                                            {{ $item['order']->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ date('d/m/Y', strtotime($item['order']->request_date)) }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->product->code }} - {{ $item['order']->product->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>
                                        {{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : 'N/A' }}
                                    </td>
                                    <td class="{{ $item['efficiency'] >= 95 ? 'text-success' : ($item['efficiency'] >= 85 ? 'text-warning' : 'text-danger') }}">
                                        {{ number_format($item['efficiency'], 2) }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay datos para mostrar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <!-- General Report -->
                <h5 class="mb-3">Reporte General</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Tipo de Servicio</th>
                                <th>Proveedor</th>
                                <th>Producto</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Compromiso</th>
                                <th>Cantidad (kg)</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('service-orders.show', $order) }}">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->serviceType->name }}</td>
                                    <td>{{ $order->provider->name }}</td>
                                    <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                    <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($order->commitment_date)) }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
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
                                    <td colspan="8" class="text-center">No hay datos para mostrar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection