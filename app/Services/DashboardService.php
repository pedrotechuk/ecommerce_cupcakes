<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Métricas do painel administrativo. Por definição do negócio (seção 27 do
 * briefing), faturamento só conta pedidos com status "delivered" — pedidos
 * ainda em andamento ou cancelados não entram na receita.
 */
class DashboardService
{
    public function totalRevenue(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) $this->deliveredOrdersQuery($from, $to)->sum('total');
    }

    public function deliveredOrdersCount(?Carbon $from = null, ?Carbon $to = null): int
    {
        return $this->deliveredOrdersQuery($from, $to)->count();
    }

    public function averageTicket(?Carbon $from = null, ?Carbon $to = null): float
    {
        $count = $this->deliveredOrdersCount($from, $to);

        return $count > 0 ? round($this->totalRevenue($from, $to) / $count, 2) : 0.0;
    }

    public function ordersCountByStatus(): Collection
    {
        return Order::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function topSellingProducts(int $limit = 5): Collection
    {
        return OrderItem::query()
            ->select('product_id', 'product_name', DB::raw('sum(quantity) as units_sold'), DB::raw('sum(subtotal) as revenue'))
            ->whereHas('order', fn ($query) => $query->where('status', 'delivered'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get();
    }

    public function revenueGrowth(): float
    {
        $currentMonth = $this->totalRevenue(now()->startOfMonth(), now()->endOfMonth());
        $previousMonth = $this->totalRevenue(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth());

        if ($previousMonth <= 0) {
            return $currentMonth > 0 ? 100.0 : 0.0;
        }

        return round((($currentMonth - $previousMonth) / $previousMonth) * 100, 1);
    }

    public function salesByDay(int $days = 30): Collection
    {
        return $this->deliveredOrdersQuery(now()->subDays($days))
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('sum(total) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    private function deliveredOrdersQuery(?Carbon $from = null, ?Carbon $to = null)
    {
        $query = Order::query()->where('status', 'delivered');

        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        return $query;
    }
}
