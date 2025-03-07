<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KardexController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['product', 'provider', 'reception']);
        
        // Apply filters
        if ($request->has('order_number') && $request->order_number) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->has('start_date') && $request->start_date) {
            $query->where('request_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->where('request_date', '<=', $request->end_date);
        }
        
        $orders = $query->orderBy('request_date', 'desc')->get();
        
        // Calculate totals
        $totals = [
            'output_kg' => $orders->sum('quantity_kg'),
            'input_kg' => $orders->sum(function ($order) {
                return $order->reception ? $order->reception->received_quantity_kg : 0;
            }),
            'scrap_kg' => $orders->sum(function ($order) {
                return $order->reception ? $order->reception->scrap_quantity_kg : 0;
            }),
        ];
        
        $totals['difference_kg'] = $totals['output_kg'] - $totals['input_kg'] - $totals['scrap_kg'];
        
        $products = Product::all();
        
        return view('kardex.index', compact('orders', 'products', 'totals'));
    }
}