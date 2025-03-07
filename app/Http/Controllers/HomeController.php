<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Count statistics
        $totalOrders = ServiceOrder::count();
        $pendingOrders = ServiceOrder::where('status', 'pending')->count();
        $completedOrders = ServiceOrder::where('status', 'completed')->count();
        $materialInProcess = ServiceOrder::where('status', 'pending')->sum('quantity_kg');
        
        // Recent orders
        $recentOrders = ServiceOrder::with(['provider', 'product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Service type statistics
        $serviceTypeStats = ServiceType::select('service_types.name', DB::raw('COUNT(service_orders.id) as count'))
            ->leftJoin('service_orders', 'service_types.id', '=', 'service_orders.service_type_id')
            ->groupBy('service_types.id', 'service_types.name')
            ->having('count', '>', 0)
            ->orderBy('count', 'desc')
            ->get();
        
        // Provider statistics
        $providerStats = Provider::select('providers.name', DB::raw('COUNT(service_orders.id) as count'))
            ->leftJoin('service_orders', 'providers.id', '=', 'service_orders.provider_id')
            ->groupBy('providers.id', 'providers.name')
            ->having('count', '>', 0)
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
        
        // Monthly statistics
        $monthlyStats = ServiceOrder::select(DB::raw('DATE_FORMAT(request_date, "%b %Y") as month'), DB::raw('COUNT(*) as count'))
            ->groupBy('month')
            ->orderBy('request_date')
            ->take(6)
            ->get();
        
        return view('home', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'materialInProcess',
            'recentOrders',
            'serviceTypeStats',
            'providerStats',
            'monthlyStats'
        ));
    }
}