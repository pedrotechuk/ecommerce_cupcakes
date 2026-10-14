<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Pedidos</h2>
    </x-slot>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="status" class="rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
            <option value="">Todos os status</option>
            @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
        <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
        <x-button variant="primary" size="sm">Filtrar</x-button>
        @if (request()->anyFilled(['status', 'from', 'to']))
            <x-button variant="secondary" size="sm" :href="route('admin.orders.index')">Limpar</x-button>
        @endif
    </form>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-mocha-400 border-b border-cream-200">
                    <th class="p-4">Pedido</th>
                    <th class="p-4">Cliente</th>
                    <th class="p-4">Data</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-cream-100 hover:bg-cream-50 transition">
                        <td class="p-4 text-mocha-800">#{{ $order->id }}</td>
                        <td class="p-4 text-mocha-500">{{ $order->user->name }}</td>
                        <td class="p-4 text-mocha-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-mocha-500">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                        <td class="p-4">
                            <x-order-status-badge :status="$order->status" />
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-brand-600 hover:text-brand-800">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-mocha-400">Nenhum pedido encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-admin-layout>
