<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Catálogo</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-3 mb-6">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-mocha-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.65 4.65a7.5 7.5 0 0011.999 11.999z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar cupcake..."
                       class="w-full rounded-lg border-mocha-200 pl-9 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <select name="category" class="rounded-lg border-mocha-200 focus:border-brand-500 focus:ring-brand-500">
                <option value="">Todas as categorias</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <x-button variant="primary" type="submit">Filtrar</x-button>
            @if (request('search') || request('category'))
                <x-button variant="ghost" type="button" :href="route('products.index')">Limpar</x-button>
            @endif
        </form>

        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}"
                   class="px-3 py-1.5 rounded-full text-sm font-medium border transition {{ ! request('category') ? 'bg-brand-600 border-brand-600 text-white' : 'bg-white border-cream-200 text-mocha-600 hover:border-brand-300' }}">
                    Todas
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', array_filter(['search' => request('search'), 'category' => $category->slug])) }}"
                       class="px-3 py-1.5 rounded-full text-sm font-medium border transition {{ request('category') === $category->slug ? 'bg-brand-600 border-brand-600 text-white' : 'bg-white border-cream-200 text-mocha-600 hover:border-brand-300' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if ($products->isEmpty())
            <x-empty-state icon="🔍" title="Nenhum cupcake encontrado" description="Tente ajustar sua busca ou remover os filtros aplicados.">
                <x-slot:action>
                    <x-button variant="secondary" :href="route('products.index')">Limpar filtros</x-button>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    @include('products.partials.card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
