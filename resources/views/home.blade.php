<x-app-layout>
    @php
        $categoryIcons = [
            'cupcakes-classicos' => '🧁',
            'cupcakes-recheados' => '🍫',
            'cupcakes-veganos' => '🌱',
            'cupcakes-sem-gluten' => '🌾',
            'edicoes-especiais' => '✨',
        ];
    @endphp

    <section class="bg-gradient-to-b from-brand-50 to-cream-100 py-20 sm:py-28">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-4xl sm:text-5xl font-semibold text-mocha-900 leading-tight">
                Cupcakes artesanais, feitos com carinho <span aria-hidden="true">🧁</span>
            </h1>
            <p class="mt-4 text-lg text-mocha-500 max-w-2xl mx-auto">
                Descubra os sabores da Cupcakes e peça o seu ainda hoje, direto da nossa cozinha para a sua mesa.
            </p>
            <x-button variant="primary" size="lg" :href="route('products.index')" class="mt-8">
                Ver catálogo completo
            </x-button>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <h2 class="font-display text-2xl font-semibold text-mocha-900 mb-6">Categorias</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                   class="flex flex-col items-center gap-2 rounded-2xl bg-white border border-cream-200 p-5 text-center transition hover:border-brand-300 hover:shadow-md">
                    <span class="text-3xl" aria-hidden="true">{{ $categoryIcons[$category->slug] ?? '🧁' }}</span>
                    <span class="text-sm font-medium text-mocha-700">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bg-white py-14 border-y border-cream-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-display text-2xl font-semibold text-mocha-900">Novidades</h2>
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                    Ver tudo →
                </a>
            </div>

            @if ($featuredProducts->isEmpty())
                <x-empty-state icon="🧁" title="Novos sabores chegando em breve" description="Ainda estamos preparando nossas novidades. Volte em instantes!" />
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($featuredProducts as $product)
                        @include('products.partials.card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <div class="flex flex-col items-center gap-2">
                <span class="text-3xl" aria-hidden="true">🌸</span>
                <h3 class="font-semibold text-mocha-800">Sempre fresquinho</h3>
                <p class="text-sm text-mocha-500">Produção diária, sem estoque parado.</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-3xl" aria-hidden="true">🔒</span>
                <h3 class="font-semibold text-mocha-800">Pagamento seguro</h3>
                <p class="text-sm text-mocha-500">Cartão ou PIX, com confirmação instantânea.</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <span class="text-3xl" aria-hidden="true">💛</span>
                <h3 class="font-semibold text-mocha-800">Feito com cuidado</h3>
                <p class="text-sm text-mocha-500">Cada cupcake preparado à mão, com ingredientes selecionados.</p>
            </div>
        </div>
    </section>
</x-app-layout>
