<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Eficiencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-success {
            color: green;
        }
        .text-warning {
            color: orange;
        }
        .text-danger {
            color: red;
        }
        .efficiency-high {
            color: green;
            font-weight: bold;
        }
        .efficiency-medium {
            color: orange;
            font-weight: bold;
        }
        .efficiency-low {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE EFICIENCIA DE SERVICIOS</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    
    <table>
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
                    <td>{{ $item['order']->order_number }}</td>
                    <td>{{ $item['order']->product->name }}</td>
                    <td>{{ $item['order']->provider->name }}</td>
                    <td>{{ $item['order']->serviceType->name }}</td>
                    <td>{{ number_format($item['order']->quantity_kg, 2) }}</td>
                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->received_quantity_kg, 2) : '-' }}</td>
                    <td>{{ $item['order']->reception ? number_format($item['order']->reception->scrap_quantity_kg, 2) : '-' }}</td>
                    <td class="{{ $item['efficiency'] >= 95 ? 'efficiency-high' : ($item['efficiency'] >= 85 ? 'efficiency-medium' : 'efficiency-low') }}">
                        {{ number_format($item['efficiency'], 2) }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay registros que coincidan con los filtros</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">PROMEDIO DE EFICIENCIA:</th>
                <th colspan="4">
                    @php
                        $avgEfficiency = $efficiency->avg('efficiency');
                    @endphp
                    <span class="{{ $avgEfficiency >= 95 ? 'efficiency-high' : ($avgEfficiency >= 85 ? 'efficiency-medium' : 'efficiency-low') }}">
                        {{ number_format($avgEfficiency, 2) }}%
                    </span>
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Sistema de Trazabilidad de Servicios - Empaques Probolsas</p>
    </div>
</body>
</html>