<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboard): View
    {
        return view('admin.dashboard', [
            'totalRevenue' => $dashboard->totalRevenue(),
            'deliveredOrdersCount' => $dashboard->deliveredOrdersCount(),
            'averageTicket' => $dashboard->averageTicket(),
            'ordersByStatus' => $dashboard->ordersCountByStatus(),
            'topProducts' => $dashboard->topSellingProducts(5),
            'revenueGrowth' => $dashboard->revenueGrowth(),
            'salesByDay' => $dashboard->salesByDay(30),
        ]);
    }
}
