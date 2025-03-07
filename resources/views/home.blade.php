@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Dashboard</h1>
        <a href="{{ route('service-orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Orden de Servicio
        </a>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Órdenes Totales</h6>
                            <h2 class="mb-0">{{ $totalOrders }}</h2>
                        </div>
                        <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('service-orders.index') }}" class="text-white text-decoration-none">Ver detalles</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Órdenes Pendientes</h6>
                            <h2 class="mb-0">{{ $pendingOrders }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('service-orders.index', ['status' => 'pending']) }}" class="text-white text-decoration-none">Ver detalles</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Órdenes Completadas</h6>
                            <h2 class="mb-0">{{ $completedOrders }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('service-orders.index', ['status' => 'completed']) }}" class="text-white text-decoration-none">Ver detalles</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Material en Proceso</h6>
                            <h2 class="mb-0">{{ number_format($materialInProcess, 2) }} kg</h2>
                        </div>
                        <i class="fas fa-balance-scale fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('kardex.index') }}" class="text-white text-decoration-none">Ver detalles</a>
                    <i class="fas fa-arrow-right text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Órdenes Recientes</h5>
                    <a href="{{ route('service-orders.index') }}" class="btn btn-sm btn-primary">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Proveedor</th>
                                    <th>Producto</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->provider->name }}</td>
                                        <td>{{ $order->product->name }}</td>
                                        <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">Pendiente</span>
                                            @else
                                                <span class="badge bg-success">Completado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('service-orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay órdenes recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Servicios por Tipo</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviceTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Órdenes por Proveedor</h5>
                </div>
                <div class="card-body">
                    <canvas id="providerChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Órdenes por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Service Type Chart
        var serviceTypeCtx = document.getElementById('serviceTypeChart').getContext('2d');
        var serviceTypeChart = new Chart(serviceTypeCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($serviceTypeStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($serviceTypeStats->pluck('count')) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Provider Chart
        var providerCtx = document.getElementById('providerChart').getContext('2d');
        var providerChart = new Chart(providerCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($providerStats->pluck('name')) !!},
                datasets: [{
                    label: 'Número de Órdenes',
                    data: {!! json_encode($providerStats->pluck('count')) !!},
                    backgroundColor: '#4e73df',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Monthly Chart
        var monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        var monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyStats->pluck('month')) !!},
                datasets: [{
                    label: 'Órdenes por Mes',
                    data: {!! json_encode($monthlyStats->pluck('count')) !!},
                    borderColor: '#4e73df',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endsection