<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-display font-semibold text-xl text-mocha-900">Pedido #{{ $order->id }}</h2>
            <x-order-status-badge :status="$order->status" />
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h3 class="font-semibold text-mocha-900 mb-4">Itens</h3>
                <div class="divide-y divide-cream-100">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="text-mocha-800">{{ $item->product_name }}</div>
                                <div class="text-sm text-mocha-500">{{ $item->quantity }}x R$ {{ number_format($item->unit_price, 2, ',', '.') }}</div>
                            </div>
                            <div class="text-right font-semibold text-mocha-800">
                                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 space-y-2">
                <h3 class="font-semibold text-mocha-900 mb-2">Resumo</h3>
                <div class="flex justify-between text-sm text-mocha-500">
                    <span>Subtotal</span>
                    <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                </div>
                @if ($order->discount > 0)
                    <div class="flex justify-between text-sm text-mocha-500">
                        <span>Desconto {{ $order->coupon_code ? "({$order->coupon_code})" : '' }}</span>
                        <span>- R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm text-mocha-500">
                    <span>Frete</span>
                    <span>R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-mocha-900 pt-2 border-t border-cream-200">
                    <span>Total</span>
                    <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h3 class="font-semibold text-mocha-900 mb-3">Cliente</h3>
                <div class="text-sm text-mocha-700">{{ $order->user->name }}</div>
                <div class="text-sm text-mocha-500">{{ $order->user->email }}</div>

                <h3 class="font-semibold text-mocha-900 mt-4 mb-2">Entrega</h3>
                <div class="text-sm text-mocha-500">
                    {{ $order->shipping_street }}, {{ $order->shipping_number }} — {{ $order->shipping_city }}
                </div>

                <h3 class="font-semibold text-mocha-900 mt-4 mb-2">Pagamento</h3>
                <div class="text-sm text-mocha-500">{{ $order->paymentLabel() }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h3 class="font-semibold text-mocha-900 mb-3">Status do pedido</h3>

                @error('status')
                    <div class="mb-3 text-sm text-red-600">{{ $message }}</div>
                @enderror

                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
                        @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-button variant="primary" class="w-full justify-center">Atualizar status</x-button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
