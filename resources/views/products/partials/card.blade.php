<div class="group bg-white rounded-2xl shadow-sm border border-cream-200 overflow-hidden flex flex-col transition duration-200 hover:shadow-lg hover:-translate-y-0.5">
    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden bg-cream-50">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-40 object-cover transition duration-300 group-hover:scale-105">

        @if (! $product->isAvailable())
            <span class="absolute top-2 right-2"><x-badge variant="danger">Esgotado</x-badge></span>
        @elseif ($product->stock <= 5)
            <span class="absolute top-2 right-2"><x-badge variant="warning">Últimas unidades</x-badge></span>
        @endif
    </a>

    <div class="p-4 flex flex-col flex-1">
        <div class="text-xs font-medium text-brand-600 uppercase tracking-wide">{{ $product->category->name }}</div>

        <a href="{{ route('products.show', $product) }}" class="font-semibold text-mocha-900 hover:text-brand-600 mt-1 line-clamp-2 transition-colors">
            {{ $product->name }}
        </a>

        @if ($product->reviews_count > 0)
            <div class="flex items-center gap-1.5 mt-1.5">
                <x-rating-stars :rating="$product->average_rating" size="sm" />
                <span class="text-xs text-mocha-400">({{ $product->reviews_count }})</span>
            </div>
        @endif

        <div class="mt-auto pt-3">
            <span class="font-display text-lg font-bold text-brand-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
        </div>
    </div>
</div>
