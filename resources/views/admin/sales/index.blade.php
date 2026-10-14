<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Histórico de Vendas</h2>
    </x-slot>

    <form method="GET" action="{{ route('admin.sales.index') }}" class="flex flex-wrap gap-3 mb-6">
        <input type="date" name="from" value="{{ request('from') }}" class="rounded-md border-gray-300 shadow-sm focus:border-pink-400 focus:ring-pink-400">
        <input type="date" name="to" value="{{ request('to') }}" class="rounded-md border-gray-300 shadow-sm focus:border-pink-400 focus:ring-pink-400">
        <button type="submit" class="bg-pink-600 text-white px-5 py-2 rounded-md hover:bg-pink-700">Filtrar</button>
        @if (request()->anyFilled(['from', 'to']))
            <a href="{{ route('admin.sales.index') }}" class="px-5 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">Limpar</a>
        @endif
    </form>

    <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
        <div class="text-sm text-gray-500">Faturamento no período (apenas pedidos entregues)</div>
        <div class="text-2xl font-bold text-gray-800 mt-1">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="p-4">Pedido</th>
                    <th class="p-4">Cliente</th>
                    <th class="p-4">Data</th>
                    <th class="p-4">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $order)
                    <tr class="border-b border-gray-50">
                        <td class="p-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-pink-600 hover:text-pink-800">#{{ $order->id }}</a>
                        </td>
                        <td class="p-4 text-gray-600">{{ $order->user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-gray-600">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">Nenhuma venda entregue neste período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $sales->links() }}
    </div>
</x-admin-layout>
