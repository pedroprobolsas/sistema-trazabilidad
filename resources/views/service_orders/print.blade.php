<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio {{ $serviceOrder->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info th {
            text-align: left;
            width: 30%;
            padding: 5px;
        }
        .order-info td {
            padding: 5px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .signatures {
            margin-top: 50px;
        }
        .signature-box {
            float: left;
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
            margin: 0 2.5%;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
        <h1>ORDEN DE SERVICIO</h1>
        <p>No. {{ $serviceOrder->order_number }}</p>
    </div>
    
    <div class="order-info section">
        <div class="section-title">Información General</div>
        <table>
            <tr>
                <th>Tipo de Servicio:</th>
                <td>{{ $serviceOrder->serviceType->name }}</td>
                <th>Fecha de Solicitud:</th>
                <td>{{ date('d/m/Y', strtotime($serviceOrder->request_date)) }}</td>
            </tr>
            <tr>
                <th>Proveedor:</th>
                <td>{{ $serviceOrder->provider->name }}</td>
                <th>Fecha de Compromiso:</th>
                <td>{{ date('d/m/Y', strtotime($serviceOrder->commitment_date)) }}</td>
            </tr>
            <tr>
                <th>NIT Proveedor:</th>
                <td>{{ $serviceOrder->provider->tax_id }}</td>
                <th>Estado:</th>
                <td>{{ $serviceOrder->status == 'pending' ? 'Pendiente' : 'Completado' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">Información del Producto</div>
        <table>
            <tr>
                <th>Código:</th>
                <td>{{ $serviceOrder->product->code }}</td>
                <th>Cantidad (kg):</th>
                <td>{{ number_format($serviceOrder->quantity_kg, 2) }}</td>
            </tr>
            <tr>
                <th>Nombre:</th>
                <td>{{ $serviceOrder->product->name }}</td>
                <th>Cantidad (unidades):</th>
                <td>{{ number_format($serviceOrder->quantity_units) }}</td>
            </tr>
            <tr>
                <th>Descripción:</th>
                <td>{{ $serviceOrder->product->description }}</td>
                <th>Costo del Servicio:</th>
                <td>${{ number_format($serviceOrder->service_cost, 2) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">Información de Entrega</div>
        <table>
            <tr>
                <th>Placa del Vehículo:</th>
                <td>{{ $serviceOrder->delivery_vehicle_plate }}</td>
                <th>Identificación del Conductor:</th>
                <td>{{ $serviceOrder->delivery_driver_id }}</td>
            </tr>
            <tr>
                <th>Nombre del Conductor:</th>
                <td>{{ $serviceOrder->delivery_driver_name }}</td>
                <th>Teléfono del Conductor:</th>
                <td>{{ $serviceOrder->delivery_driver_phone }}</td>
            </tr>
        </table>
    </div>
    
    <div class="signatures clearfix">
        <div class="signature-box">
            <p>Entregado por:</p>
            <p>{{ $serviceOrder->deliveredByUser->name }}</p>
            <p>Empaques Probolsas</p>
        </div>
        
        <div class="signature-box">
            <p>Recibido por:</p>
            <p>{{ $serviceOrder->received_by }}</p>
            <p>{{ $serviceOrder->provider->name }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Este documento es una constancia de entrega de material para servicio externo.</p>
        <p>Sistema de Trazabilidad de Servicios - Empaques Probolsas</p>
    </div>
</body>
</html>