<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ServiceOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $orders;
    protected $reportType;

    public function __construct($orders, $reportType = 'general')
    {
        $this->orders = $orders;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        if ($this->reportType == 'efficiency') {
            return $this->orders;
        }
        
        return $this->orders;
    }

    public function headings(): array
    {
        if ($this->reportType == 'efficiency') {
            return [
                'Orden',
                'Producto',
                'Proveedor',
                'Tipo de Servicio',
                'Material Enviado (kg)',
                'Material Recibido (kg)',
                'Retales (kg)',
                'Eficiencia (%)'
            ];
        }
        
        return [
            'Orden',
            'Tipo de Servicio',
            'Proveedor',
            'Producto',
            'Fecha Solicitud',
            'Fecha Compromiso',
            'Cantidad (kg)',
            'Estado'
        ];
    }

    public function map($row): array
    {
        if ($this->reportType == 'efficiency') {
            $order = $row['order'];
            return [
                $order->order_number,
                $order->product->name,
                $order->provider->name,
                $order->serviceType->name,
                number_format($order->quantity_kg, 2),
                $order->reception ? number_format($order->reception->received_quantity_kg, 2) : 'N/A',
                $order->reception ? number_format($order->reception->scrap_quantity_kg, 2) : 'N/A',
                number_format($row['efficiency'], 2) . '%'
            ];
        }
        
        return [
            $row->order_number,
            $row->serviceType->name,
            $row->provider->name,
            $row->product->code . ' - ' . $row->product->name,
            date('d/m/Y', strtotime($row->request_date)),
            date('d/m/Y', strtotime($row->commitment_date)),
            number_format($row->quantity_kg, 2),
            $row->status == 'pending' ? 'Pendiente' : 'Completado'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}