<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE GENERAL DE ÓRDENES DE SERVICIO</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    
    <table>
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
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->serviceType->name }}</td>
                    <td>{{ $order->provider->name }}</td>
                    <td>{{ $order->product->code }} - {{ $order->product->name }}</td>
                    <td>{{ date('d/m/Y', strtotime($order->request_date)) }}</td>
                    <td>{{ date('d/m/Y', strtotime($order->commitment_date)) }}</td>
                    <td>{{ number_format($order->quantity_kg, 2) }}</td>
                    <td>{{ $order->status == 'pending' ? 'Pendiente' : 'Completado' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay datos para mostrar</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Sistema de Trazabilidad de Servicios - Empaques Probolsas</p>
    </div>
</body>
</html>