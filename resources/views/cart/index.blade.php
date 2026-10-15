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
                    items: @json($cartItemsForJs),
                    subtotal: {{ $cart->subtotal() }},
                    shipping: {{ $shippingCost }},
                    decrement(item) {
                        item.qty = Math.max(0, item.qty - 1);
                        this.queueUpdate(item);
                    },
                    increment(item) {
                        item.qty = Math.min(item.max, item.qty + 1);
                        this.queueUpdate(item);
                    },
                    queueUpdate(item) {
                        clearTimeout(item.timer);
                        item.timer = setTimeout(() => this.save(item), 500);
                    },
                    async save(item) {
                        item.error = null;
                        const previousQty = item.qty;
                        try {
                            const { data } = await axios.patch(item.update_url, { quantity: item.qty });
                            this.subtotal = data.subtotal;
                            this.shipping = data.shipping;
                            if (data.removed) {
                                this.items = this.items.filter(i => i.product_id !== item.product_id);
                            }
                        } catch (e) {
                            item.error = e.response?.data?.errors?.quantity?.[0] ?? 'Não foi possível atualizar o carrinho.';
                            item.qty = previousQty;
                        }
                    },
                    async remove(item) {
                        try {
                            const { data } = await axios.delete(item.destroy_url);
                            this.subtotal = data.subtotal;
                            this.shipping = data.shipping;
                            this.items = this.items.filter(i => i.product_id !== item.product_id);
                        } catch (e) {
                            item.error = 'Não foi possível remover o produto.';
                        }
                    },
                 }"
            >
                <div x-show="items.length > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
                    <template x-for="item in items" :key="item.product_id">
                        <div class="p-4 flex flex-wrap sm:flex-nowrap items-center gap-4">
                            <img :src="item.image" :alt="item.name" class="w-16 h-16 object-cover rounded-lg shrink-0">

                            <div class="flex-1 min-w-[140px]">
                                <a :href="item.url" class="font-medium text-mocha-900 hover:text-brand-600" x-text="item.name"></a>
                                <div class="text-sm text-mocha-400" x-text="formatBRL(item.price) + ' / un.'"></div>
                                <p x-show="item.error" x-text="item.error" class="text-xs text-red-500 mt-1"></p>
                            </div>

                            <div class="flex items-center border border-mocha-200 rounded-lg overflow-hidden">
                                <button type="button" @click="decrement(item)"
                                        class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Diminuir quantidade">−</button>
                                <input type="number" x-model.number="item.qty" @input="queueUpdate(item)" min="0" :max="item.max"
                                       class="w-12 border-0 text-center text-sm focus:ring-0 p-0 py-1.5">
                                <button type="button" @click="increment(item)"
                                        class="px-2.5 py-1.5 text-mocha-500 hover:bg-cream-100 focus:outline-none" aria-label="Aumentar quantidade">+</button>
                            </div>

                            <div class="w-24 text-right font-semibold text-mocha-900" x-text="formatBRL(item.qty * item.price)"></div>

                            <button type="button" @click="remove(item)" class="text-sm text-red-500 hover:text-red-700">Remover</button>
                        </div>
                    </template>
                </div>

                <div x-show="items.length > 0" class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 mt-6 space-y-2">
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

                <div x-show="items.length === 0" class="bg-white rounded-2xl shadow-sm border border-cream-200">
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
