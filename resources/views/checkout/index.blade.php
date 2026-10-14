<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Finalizar Compra</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-center gap-2 mb-8 text-xs sm:text-sm font-medium text-mocha-400">
            <span class="text-brand-600">Carrinho</span>
            <span aria-hidden="true">→</span>
            <span class="text-mocha-900 font-semibold">Entrega e pagamento</span>
            <span aria-hidden="true">→</span>
            <span>Confirmação</span>
        </div>

        @if ($addresses->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200">
                <x-empty-state icon="📍" title="Cadastre um endereço para continuar" description="Você precisa de um endereço de entrega antes de finalizar a compra.">
                    <x-slot:action>
                        <x-button variant="primary" :href="route('addresses.index')">Cadastrar endereço</x-button>
                    </x-slot:action>
                </x-empty-state>
            </div>
        @else
            <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf

                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                        <h3 class="font-semibold text-mocha-900 mb-4">Endereço de entrega</h3>
                        <div class="space-y-2">
                            @foreach ($addresses as $address)
                                <label class="flex items-start gap-3 p-3 border border-cream-200 rounded-xl cursor-pointer transition has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="mt-1 text-brand-600 focus:ring-brand-500"
                                           @checked($address->is_default || $loop->first) required>
                                    <span class="text-sm text-mocha-700">
                                        {{ $address->street }}, {{ $address->number }}
                                        {{ $address->complement ? '— ' . $address->complement : '' }} — {{ $address->city }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <a href="{{ route('addresses.index') }}" class="inline-block mt-3 text-sm text-brand-600 hover:text-brand-700">
                            Gerenciar endereços
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                        <h3 class="font-semibold text-mocha-900 mb-4">Forma de pagamento</h3>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 text-sm text-mocha-700 px-4 py-2.5 border border-cream-200 rounded-xl cursor-pointer transition has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                                <input type="radio" name="payment_method" value="card" class="text-brand-600 focus:ring-brand-500" checked required>
                                Cartão
                            </label>
                            <label class="flex items-center gap-2 text-sm text-mocha-700 px-4 py-2.5 border border-cream-200 rounded-xl cursor-pointer transition has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                                <input type="radio" name="payment_method" value="pix" class="text-brand-600 focus:ring-brand-500" required>
                                PIX
                            </label>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                        <h3 class="font-semibold text-mocha-900 mb-4">Cupom de desconto</h3>
                        <x-text-input type="text" name="coupon_code" placeholder="Código do cupom (opcional)" class="w-full" />
                        <p class="text-xs text-mocha-400 mt-2">O desconto será validado e aplicado ao confirmar o pedido.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 h-fit space-y-3 md:sticky md:top-24">
                    <h3 class="font-semibold text-mocha-900 mb-2">Resumo do pedido</h3>

                    @foreach ($cart->items as $item)
                        <div class="flex justify-between text-sm text-mocha-500">
                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span>R$ {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="flex justify-between text-sm text-mocha-500 pt-2 border-t border-cream-200">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($cart->subtotal(), 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-mocha-500">
                        <span>Frete</span>
                        <span>{{ $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis' }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-mocha-900 pt-2 border-t border-cream-200">
                        <span>Total estimado</span>
                        <span>R$ {{ number_format($cart->subtotal() + $shippingCost, 2, ',', '.') }}</span>
                    </div>

                    <x-button variant="primary" size="lg" type="submit" class="w-full">
                        Confirmar pedido
                    </x-button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
