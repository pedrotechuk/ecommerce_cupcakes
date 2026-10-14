<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Meu Carrinho</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($cart->items->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200">
                <x-empty-state icon="🛒" title="Seu carrinho está vazio" description="Que tal escolher alguns cupcakes deliciosos para começar?">
                    <x-slot:action>
                        <x-button variant="primary" :href="route('products.index')">Ver catálogo</x-button>
                    </x-slot:action>
                </x-empty-state>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
                @foreach ($cart->items as $item)
                    <div class="p-4 flex flex-wrap sm:flex-nowrap items-center gap-4">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg shrink-0">

                        <div class="flex-1 min-w-[140px]">
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium text-mocha-900 hover:text-brand-600">
                                {{ $item->product->name }}
                            </a>
                            <div class="text-sm text-mocha-400">R$ {{ number_format($item->product->price, 2, ',', '.') }} / un.</div>
                        </div>

                        <form method="POST" action="{{ route('cart.update', $item->product) }}"
                              x-data="{ qty: {{ $item->quantity }}, max: {{ $item->product->stock }} }"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center border border-mocha-200 rounded-lg overflow-hidden">
                                <button type="button" @click="qty = Math.max(0, qty - 1)"
                                        class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Diminuir quantidade">−</button>
                                <input type="number" name="quantity" x-model.number="qty" min="0" :max="max"
                                       class="w-12 border-0 text-center text-sm focus:ring-0 p-0 py-1.5">
                                <button type="button" @click="qty = Math.min(max, qty + 1)"
                                        class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Aumentar quantidade">+</button>
                            </div>
                            <button type="submit" class="text-sm font-medium text-brand-600 hover:text-brand-700">Atualizar</button>
                        </form>

                        <div class="w-24 text-right font-semibold text-mocha-900">
                            R$ {{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}
                        </div>

                        <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remover</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 mt-6 space-y-2">
                <div class="flex justify-between text-mocha-500">
                    <span>Subtotal</span>
                    <span>R$ {{ number_format($cart->subtotal(), 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-mocha-500">
                    <span>Frete estimado</span>
                    <span>{{ $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis' }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-mocha-900 pt-2 border-t border-cream-200">
                    <span>Total</span>
                    <span>R$ {{ number_format($cart->subtotal() + $shippingCost, 2, ',', '.') }}</span>
                </div>

                <x-button variant="primary" size="lg" :href="route('checkout.index')" class="mt-4 w-full">
                    Finalizar compra
                </x-button>
            </div>
        @endif
    </div>
</x-app-layout>
