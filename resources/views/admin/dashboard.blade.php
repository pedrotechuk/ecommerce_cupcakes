<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Dashboard</h2>
    </x-slot>

    @php
        $lowStockProducts = \App\Models\Product::where('is_active', true)->where('stock', '<=', 5)->orderBy('stock')->get();
    @endphp

    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-5">
                <div class="text-sm text-mocha-400">💰 Faturamento (entregues)</div>
                <div class="text-2xl font-bold text-mocha-900 mt-1">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-5">
                <div class="text-sm text-mocha-400">📦 Pedidos entregues</div>
                <div class="text-2xl font-bold text-mocha-900 mt-1">{{ $deliveredOrdersCount }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-5">
                <div class="text-sm text-mocha-400">🎟️ Ticket médio</div>
                <div class="text-2xl font-bold text-mocha-900 mt-1">R$ {{ number_format($averageTicket, 2, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-5">
                <div class="text-sm text-mocha-400">📈 Crescimento mensal</div>
                <div class="text-2xl font-bold mt-1 {{ $revenueGrowth >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%
                </div>
            </div>
        </div>

        @if ($lowStockProducts->isNotEmpty())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                <h3 class="font-semibold text-amber-800 flex items-center gap-2 mb-3">
                    <span aria-hidden="true">⚠️</span> Estoque baixo
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($lowStockProducts as $product)
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-sm bg-white border border-amber-200 rounded-full px-3 py-1 text-amber-800 hover:border-amber-400 transition">
                            {{ $product->name }} — {{ $product->stock }} un.
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 lg:col-span-2">
                <h3 class="font-semibold text-mocha-900 mb-4">Vendas por dia (últimos 30 dias)</h3>
                <canvas id="salesChart" height="100"></canvas>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h3 class="font-semibold text-mocha-900 mb-4">Pedidos por status</h3>
                <div class="space-y-2">
                    @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                        <div class="flex justify-between text-sm text-mocha-500">
                            <span>{{ $label }}</span>
                            <span class="font-medium text-mocha-800">{{ $ordersByStatus[$status] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
            <h3 class="font-semibold text-mocha-900 mb-4">Produtos mais vendidos</h3>
            @if ($topProducts->isEmpty())
                <p class="text-sm text-mocha-400">Ainda não há vendas entregues suficientes para gerar o ranking.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-mocha-400 border-b border-cream-200">
                            <th class="py-2">Produto</th>
                            <th class="py-2">Unidades vendidas</th>
                            <th class="py-2">Receita</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topProducts as $product)
                            <tr class="border-b border-cream-100">
                                <td class="py-2 text-mocha-800">{{ $product->product_name }}</td>
                                <td class="py-2 text-mocha-600">{{ $product->units_sold }}</td>
                                <td class="py-2 text-mocha-600">R$ {{ number_format($product->revenue, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
        const salesData = @json($salesByDay);
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: salesData.map(d => d.day),
                datasets: [{
                    label: 'Faturamento (R$)',
                    data: salesData.map(d => d.total),
                    borderColor: '#DB2E68',
                    backgroundColor: 'rgba(219, 46, 104, 0.1)',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    </script>
</x-admin-layout>
