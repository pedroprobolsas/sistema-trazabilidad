<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['reception', 'product', 'provider']);
        
        if ($request->has('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('request_date', [$request->start_date, $request->end_date]);
        }
        
        $orders = $query->get();
        $products = Product::all();
        
        $totals = [
            'output_kg' => $orders->sum('quantity_kg'),
            'input_kg' => $orders->sum(function($order) {
                return $order->reception ? $order->reception->received_quantity_kg : 0;
            }),
            'scrap_kg' => $orders->sum(function($order) {
                return $order->reception ? $order->reception->scrap_quantity_kg : 0;
            }),
        ];
        
        $totals['difference_kg'] = $totals['output_kg'] - $totals['input_kg'] - $totals['scrap_kg'];
        
        return view('kardex.index', compact('orders', 'products', 'totals'));
    }
}