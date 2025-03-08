@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reportes</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Generar Reporte</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reports.generate') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="report_type" class="form-label">Tipo de Reporte</label>
                        <select name="report_type" id="report_type" class="form-select" required>
                            <option value="general">Reporte General de Órdenes</option>
                            <option value="efficiency">Reporte de Eficiencia</option>
                            <option value="receptions">Reporte de Recepciones</option>
                            <option value="inventory">Reporte de Kardex (Inventario)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pending">Pendiente</option>
                            <option value="completed">Completado</option>
                            <option value="in_process">En Proceso</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="service_type_id" class="form-label">Tipo de Servicio</label>
                        <select name="service_type_id" id="service_type_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="provider_id" class="form-label">Proveedor</label>
                        <select name="provider_id" id="provider_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="product_id" class="form-label">Producto</label>
                        <select name="product_id" id="product_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Fecha Inicial</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Fecha Final</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="export" class="form-label">Exportar Como</label>
                        <select name="export" id="export" class="form-select">
                            <option value="">Ver en Pantalla</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to show/hide fields based on report type -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeSelect = document.getElementById('report_type');
            
            reportTypeSelect.addEventListener('change', function() {
                const reportType = this.value;
                const serviceTypeField = document.getElementById('service_type_id').closest('.col-md-4');
                const statusField = document.getElementById('status').closest('.col-md-6');
                
                // Show/hide fields based on report type
                if (reportType === 'inventory') {
                    serviceTypeField.style.display = 'none';
                    statusField.style.display = 'none';
                } else {
                    serviceTypeField.style.display = 'block';
                    statusField.style.display = 'block';
                }
            });
            
            // Trigger change event on page load
            reportTypeSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection