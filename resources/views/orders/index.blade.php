<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Meus Pedidos</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($orders->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200">
                <x-empty-state icon="📦" title="Você ainda não fez nenhum pedido" description="Que tal escolher alguns cupcakes deliciosos?">
                    <x-slot:action>
                        <x-button variant="primary" :href="route('products.index')">Ver catálogo</x-button>
                    </x-slot:action>
                </x-empty-state>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-4 transition hover:bg-cream-50">
                        <div>
                            <div class="font-medium text-mocha-800">Pedido #{{ $order->id }}</div>
                            <div class="text-sm text-mocha-400">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-mocha-900 mb-1">R$ {{ number_format($order->total, 2, ',', '.') }}</div>
                            <x-order-status-badge :status="$order->status" />
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
