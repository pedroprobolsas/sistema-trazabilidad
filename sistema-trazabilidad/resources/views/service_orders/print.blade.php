<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Servicio {{ $serviceOrder->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
        }
        .order-number {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            width: 40%;
        }
        .row {
            display: flex;
            margin: 0 -10px;
        }
        .col {
            flex: 1;
            padding: 0 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        .signature {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>EMPAQUES PROBOLSAS</h1>
        <p>NIT: 900.123.456-7</p>
        <p>Dirección: Calle Principal #123, Ciudad</p>
        <p>Teléfono: (601) 123-4567</p>
        <h1>ORDEN DE SERVICIO</h1>
        <div class="order-number">{{ $serviceOrder->order_number }}</div>
    </div>

    <div class="row">
        <div class="col">
            <div class="section">
                <div class="section-title">Información General</div>
                <table>
                    <tr>
                        <th>Tipo de Servicio</th>
                        <td>{{ $serviceOrder->serviceType->name }}</td>
                    </tr>
                    <tr>
                        <th>Proveedor</th>
                        <td>{{ $serviceOrder->provider->name }}</td>
                    </tr>
                    <tr>
                        <th>Producto</th>
                        <td>{{ $serviceOrder->product->code }} - {{ $serviceOrder->product->name }}</td>
                    </tr>
                    <tr>
                        <th>Cantidad (kg)</th>
                        <td>{{ number_format($serviceOrder->quantity_kg, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Cantidad (unidades)</th>
                        <td>{{ number_format($serviceOrder->quantity_units) }}</td>
                    </tr>
                    <tr>
                        <th>Costo del Servicio</th>
                        <td>${{ number_format($serviceOrder->service_cost, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col">
            <div class="section">
                <div class="section-title">Fechas</div>
                <table>
                    <tr>
                        <th>Fecha de Solicitud</th>
                        <td>{{ date('d/m/Y', strtotime($serviceOrder->request_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Compromiso</th>
                        <td>{{ date('d/m/Y', strtotime($serviceOrder->commitment_date)) }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Información de Transporte</div>
                <table>
                    <tr>
                        <th>Placa del Vehículo</th>
                        <td>{{ $serviceOrder->delivery_vehicle_plate }}</td>
                    </tr>
                    <tr>
                        <th>Conductor</th>
                        <td>{{ $serviceOrder->delivery_driver_name }}</td>
                    </tr>
                    <tr>
                        <th>Identificación</th>
                        <td>{{ $serviceOrder->delivery_driver_id }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $serviceOrder->delivery_driver_phone }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Observaciones</div>
        <p>Esta orden de servicio está sujeta a los términos y condiciones acordados entre las partes. El proveedor se compromete a entregar el servicio en la fecha acordada y con la calidad esperada.</p>
    </div>

    <div class="signatures">
        <div class="signature">
            <div class="signature-line">Entregado por (Probolsas)</div>
        </div>
        <div class="signature">
            <div class="signature-line">Recibido por (Proveedor): {{ $serviceOrder->received_by }}</div>
        </div>
    </div>

    <div class="footer">
        <p>DOCUMENTO IMPRESO EL {{ date('d/m/Y H:i:s') }}</p>
        <p>SISTEMA DE TRAZABILIDAD DE SERVICIOS - EMPAQUES PROBOLSAS</p>
    </div>
</body>
</html>
