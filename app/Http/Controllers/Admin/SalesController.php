<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()
            ->where('status', 'delivered')
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date('to')));

        $totalRevenue = (clone $query)->sum('total');

        $sales = $query->with('user')->latest()->paginate(15)->withQueryString();

        return view('admin.sales.index', compact('sales', 'totalRevenue'));
    }
}
