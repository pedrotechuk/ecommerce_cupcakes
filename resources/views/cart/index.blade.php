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
            <div x-data="{
                    subtotal: {{ $cart->subtotal() }},
                    shipping: {{ $shippingCost }},
                    itemCount: {{ $cart->items->count() }},
                 }">
                <div x-show="itemCount > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
                    @foreach ($cart->items as $item)
                        <div
                            x-data="{
                                qty: {{ $item->quantity }},
                                max: {{ $item->product->stock }},
                                price: {{ $item->product->price }},
                                saving: false,
                                error: null,
                                timer: null,
                                get lineTotal() { return this.qty * this.price; },
                                queue() {
                                    clearTimeout(this.timer);
                                    this.timer = setTimeout(() => this.save(), 500);
                                },
                                async save() {
                                    this.saving = true;
                                    this.error = null;
                                    try {
                                        const { data } = await axios.patch('{{ route('cart.update', $item->product) }}', { quantity: this.qty });
                                        $parent.subtotal = data.subtotal;
                                        $parent.shipping = data.shipping;
                                        if (data.removed) {
                                            $parent.itemCount--;
                                            $el.remove();
                                        }
                                    } catch (e) {
                                        this.error = e.response?.data?.errors?.quantity?.[0] ?? 'Não foi possível atualizar o carrinho.';
                                        this.qty = {{ $item->quantity }};
                                    } finally {
                                        this.saving = false;
                                    }
                                },
                            }"
                            class="p-4 flex flex-wrap sm:flex-nowrap items-center gap-4"
                        >
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg shrink-0">

                            <div class="flex-1 min-w-[140px]">
                                <a href="{{ route('products.show', $item->product) }}" class="font-medium text-mocha-900 hover:text-brand-600">
                                    {{ $item->product->name }}
                                </a>
                                <div class="text-sm text-mocha-400">R$ {{ number_format($item->product->price, 2, ',', '.') }} / un.</div>
                                <p x-show="error" x-text="error" class="text-xs text-red-500 mt-1"></p>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="flex items-center border border-mocha-200 rounded-lg overflow-hidden">
                                    <button type="button" @click="qty = Math.max(0, qty - 1); queue()"
                                            class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Diminuir quantidade">−</button>
                                    <input type="number" x-model.number="qty" @input="queue()" min="0" :max="max"
                                           class="w-12 border-0 text-center text-sm focus:ring-0 p-0 py-1.5">
                                    <button type="button" @click="qty = Math.min(max, qty + 1); queue()"
                                            class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Aumentar quantidade">+</button>
                                </div>
                                <span x-show="saving" class="text-xs text-mocha-400">Salvando…</span>
                            </div>

                            <div class="w-24 text-right font-semibold text-mocha-900" x-text="formatBRL(lineTotal)"></div>

                            <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remover</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div x-show="itemCount > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 mt-6 space-y-2">
                    <div class="flex justify-between text-mocha-500">
                        <span>Subtotal</span>
                        <span x-text="formatBRL(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-mocha-500">
                        <span>Frete estimado</span>
                        <span x-text="shipping > 0 ? formatBRL(shipping) : 'Grátis'"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-mocha-900 pt-2 border-t border-cream-200">
                        <span>Total</span>
                        <span x-text="formatBRL(subtotal + shipping)"></span>
                    </div>

                    <x-button variant="primary" size="lg" :href="route('checkout.index')" class="mt-4 w-full">
                        Finalizar compra
                    </x-button>
                </div>

                <div x-show="itemCount === 0" class="bg-white rounded-2xl shadow-sm border border-cream-200">
                    <x-empty-state icon="🛒" title="Seu carrinho está vazio" description="Que tal escolher alguns cupcakes deliciosos para começar?">
                        <x-slot:action>
                            <x-button variant="primary" :href="route('products.index')">Ver catálogo</x-button>
                        </x-slot:action>
                    </x-empty-state>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
