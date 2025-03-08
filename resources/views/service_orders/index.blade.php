@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kardex de Materiales</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('kardex.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="order_number" class="form-label">Número de Orden</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="{{ request('order_number') }}">
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->code }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Salida (kg)</th>
                            <th>Entrada (kg)</th>
                            <th>Retales (kg)</th>
                            <th>Diferencia (kg)</th>
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
                                <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                                <td>{{ $order->provider->name }}</td>
                                <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                                <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->received_quantity_kg, 2) : '-' }}</td>
                                <td>{{ $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                <td>
                                    @if($order->reception)
                                        @php
                                            $difference = $order->quantity_kg - $order->reception->received_quantity_kg - $order->reception->scrap_quantity_kg;
                                        @endphp
                                        <span class="{{ $difference > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($difference, 2) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="9" class="text-center">No hay registros que coincidan con los filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="4">TOTALES</th>
                            <th>{{ number_format($totals['output_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['input_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['scrap_kg'], 2) }} kg</th>
                            <th>{{ number_format($totals['difference_kg'], 2) }} kg</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reportes</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.generate') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="service_type_id" class="form-label">Tipo de Servicio</label>
                    <select class="form-select" id="service_type_id" name="service_type_id">
                        <option value="">Todos los servicios</option>
                        @foreach($serviceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="provider_id" class="form-label">Proveedor</label>
                    <select class="form-select" id="provider_id" name="provider_id">
                        <option value="">Todos los proveedores</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reportes</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.generate') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="service_type_id" class="form-label">Tipo de Servicio</label>
                    <select class="form-select" id="service_type_id" name="service_type_id">
                        <option value="">Todos los servicios</option>
                        @foreach($serviceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="provider_id" class="form-label">Proveedor</label>
                    <select class="form-select" id="provider_id" name="provider_id">
                        <option value="">Todos los proveedores</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Producto</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">Todos los productos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
                <div class="col-md-3">
                    <label for="report_type" class="form-label">Tipo de Reporte</label>
                    <select class="form-select" id="report_type" name="report_type">
                        <option value="general">General</option>
                        <option value="efficiency">Eficiencia de Material</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="export" class="form-label">Formato</label>
                    <select class="form-select" id="export" name="export">
                        <option value="">Ver en pantalla</option>
                        <option value="excel">Exportar a Excel</option>
                        <option value="pdf">Exportar a PDF</option>
                    </select>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Resultados del Reporte</h1>
        <div>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel"></i> Exportar a Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exportar a PDF
            </a>
        </div>
    </div>

    @if(request('report_type') == 'efficiency')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Eficiencia de Material</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Tipo de Servicio</th>
                                <th>Material Enviado (kg)</th>
                                <th>Material Recibido (kg)</th>
                                <th>Retales (kg)</th>
                                <th>Eficiencia (%)</th>
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
                                    <td>{{ $item['order']->product->name }}</td>
                                    <td>{{ $item['order']->provider->name }}</td>
                                    <td>{{ $item['order']->serviceType->name }}</td>
                                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                                    <td>
                                        @if($item['efficiency'] > 0)
                                            <div class="progress">
                                                <div class="progress-bar {{ $item['efficiency'] >= 95 ? 'bg-success' : ($item['efficiency'] >= 85 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min(100, $item['efficiency']) }}%" 
                                                     aria-valuenow="{{ $item['efficiency'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ number_format($item['efficiency'], 2) }}%
                                                </div>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reporte General</h5>
            </div>
            <div class="card-body">
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
                                <th>Fecha Recepción</th>
                                <th>Cantidad (kg)</th>
                                <th>Costo</th>
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
                                    <td>{{ $order->reception ? date('d/m/Y', strtotime($order->reception->reception_date)) : '-' }}</td>
                                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                                    <td>${{ number_format($order->service_cost, 2) }}</td>
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
                                    <td colspan="10" class="text-center">No hay registros que coincidan con los filtros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tipos de Servicio</h1>
        <a href="{{ route('service-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo de Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviceTypes as $serviceType)
                            <tr>
                                <td>{{ $serviceType->name }}</td>
                                <td>{{ $serviceType->description }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('service-types.edit', $serviceType) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('service-types.destroy', $serviceType) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este tipo de servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay tipos de servicio registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection@extends('layouts.app')

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