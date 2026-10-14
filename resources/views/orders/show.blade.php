<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Pedido #{{ $order->id }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        @if (session('status') === 'Pedido realizado com sucesso!')
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-2" aria-hidden="true">🎉</div>
                <h3 class="font-display text-lg font-semibold text-emerald-800">Pedido confirmado!</h3>
                <p class="text-sm text-emerald-700 mt-1">Recebemos seu pedido e já estamos preparando tudo com carinho.</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="text-sm text-mocha-400">Realizado em {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="text-sm text-mocha-400">Pagamento: {{ $order->paymentLabel() }}</div>
                </div>
                <x-order-status-badge :status="$order->status" />
            </div>

            @if ($order->status !== 'cancelled')
                <div class="flex items-center gap-1 mb-2">
                    @php
                        $steps = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                        $currentIndex = array_search($order->status, $steps, true);
                    @endphp
                    @foreach ($steps as $index => $step)
                        <div class="flex-1 h-2 rounded-full {{ $index <= $currentIndex ? 'bg-brand-500' : 'bg-cream-200' }}"></div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xs text-mocha-400">
                    @foreach ($steps as $step)
                        <span>{{ \App\Models\Order::STATUS_LABELS[$step] }}</span>
                    @endforeach
                </div>
            @endif

            @error('status')
                <div class="mt-3 text-sm text-red-600">{{ $message }}</div>
            @enderror

            @if ($order->canBeCancelled())
                <x-confirm-dialog title="Cancelar pedido?" message="Essa ação não pode ser desfeita." confirm-label="Sim, cancelar" cancel-label="Voltar" class="mt-4 inline-block">
                    <form method="POST" action="{{ route('orders.cancel', $order) }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 border border-red-200 rounded-lg px-4 py-2 transition hover:bg-red-50">
                            Cancelar pedido
                        </button>
                    </form>
                </x-confirm-dialog>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
            <h3 class="font-semibold text-mocha-900 mb-4">Itens</h3>
            <div class="divide-y divide-cream-100">
                @foreach ($order->items as $item)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <div class="text-mocha-800">{{ $item->product_name }}</div>
                            <div class="text-sm text-mocha-400">{{ $item->quantity }}x R$ {{ number_format($item->unit_price, 2, ',', '.') }}</div>
                        </div>
                        <div class="text-right font-semibold text-mocha-900">
                            R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 space-y-2">
            <h3 class="font-semibold text-mocha-900 mb-2">Resumo</h3>
            <div class="flex justify-between text-sm text-mocha-500">
                <span>Endereço de entrega</span>
                <span class="text-right">{{ $order->shipping_street }}, {{ $order->shipping_number }} — {{ $order->shipping_city }}</span>
            </div>
            @if ($order->coupon_code)
                <div class="flex justify-between text-sm text-mocha-500">
                    <span>Cupom aplicado</span>
                    <span>{{ $order->coupon_code }}</span>
                </div>
            @endif
            <div class="flex justify-between text-sm text-mocha-500">
                <span>Subtotal</span>
                <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
            </div>
            @if ($order->discount > 0)
                <div class="flex justify-between text-sm text-mocha-500">
                    <span>Desconto</span>
                    <span>- R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between text-sm text-mocha-500">
                <span>Frete</span>
                <span>{{ $order->shipping_cost > 0 ? 'R$ ' . number_format($order->shipping_cost, 2, ',', '.') : 'Grátis' }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold text-mocha-900 pt-2 border-t border-cream-200">
                <span>Total</span>
                <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('orders.index') }}" class="inline-block text-sm text-brand-600 hover:text-brand-700">
            ← Voltar para meus pedidos
        </a>
    </div>
</x-app-layout>
