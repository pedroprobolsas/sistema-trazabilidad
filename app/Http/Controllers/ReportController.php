<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Provider;
use App\Models\Product;
use App\Exports\ServiceOrdersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::all();
        $providers = Provider::all();
        $products = Product::all();
        
        return view('reports.index', compact('serviceTypes', 'providers', 'products'));
    }

    public function generate(Request $request)
    {
        $query = ServiceOrder::with(['serviceType', 'provider', 'product', 'reception']);
        
        // Apply filters
        if ($request->has('service_type_id') && $request->service_type_id) {
            $query->where('service_type_id', $request->service_type_id);
        }
        
        if ($request->has('provider_id') && $request->provider_id) {
            $query->where('provider_id', $request->provider_id);
        }
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('start_date') && $request->start_date) {
            $query->where('request_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('request_date', '<=', $request->end_date);
        }
        
        $orders = $query->orderBy('request_date', 'desc')->get();
        
        // If efficiency report, calculate efficiency metrics
        if ($request->report_type == 'efficiency') {
            $efficiency = $orders->map(function ($order) {
                $receivedKg = $order->reception ? $order->reception->received_quantity_kg : 0;
                $scrapKg = $order->reception ? $order->reception->scrap_quantity_kg : 0;
                $efficiency = $order->quantity_kg > 0 ? (($receivedKg + $scrapKg) / $order->quantity_kg) * 100 : 0;
                
                return [
                    'order' => $order,
                    'efficiency' => $efficiency
                ];
            });
            
            // Handle exports if requested
            if ($request->has('export') && $request->export) {
                if ($request->export == 'excel') {
                    return Excel::download(new ServiceOrdersExport($orders, $request->report_type), 'reporte_eficiencia.xlsx');
                } elseif ($request->export == 'pdf') {
                    $pdf = PDF::loadView('reports.pdf_efficiency', compact('efficiency'));
                    return $pdf->download('reporte_eficiencia.pdf');
                }
            }
            
            return view('reports.show', compact('efficiency'));
        }
        
        // Handle exports for general report
        if ($request->has('export') && $request->export) {
            if ($request->export == 'excel') {
                return Excel::download(new ServiceOrdersExport($orders), 'reporte_general.xlsx');
            } elseif ($request->export == 'pdf') {
                $pdf = PDF::loadView('reports.pdf_general', compact('orders'));
                return $pdf->download('reporte_general.pdf');
            }
        }
        
        return view('reports.show', compact('orders'));
    }

    public function downloadPdf(Request $request, $reportType)
    {
        $data = $this->generate($request);
        
        if ($reportType === 'efficiency') {
            $pdf = PDF::loadView('reports.pdf_efficiency', ['efficiency' => $data]);
        } else {
            $pdf = PDF::loadView('reports.pdf_general', ['orders' => $data]);
        }
        
        return $pdf->download("reporte_{$reportType}.pdf");
    }
}