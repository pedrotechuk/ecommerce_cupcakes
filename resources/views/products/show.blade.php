<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">{{ $product->name }}</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-72 md:h-full object-cover rounded-xl bg-cream-50">

            <div class="flex flex-col">
                <div class="text-xs font-medium text-brand-600 uppercase tracking-wide">{{ $product->category->name }}</div>
                <h1 class="font-display text-2xl font-semibold text-mocha-900 mt-1">{{ $product->name }}</h1>

                @if ($product->reviews_count > 0)
                    <div class="flex items-center gap-2 mt-2">
                        <x-rating-stars :rating="$product->average_rating" />
                        <span class="text-mocha-400 text-sm">{{ $product->average_rating }} ({{ $product->reviews_count }} avaliações)</span>
                    </div>
                @endif

                <p class="text-mocha-500 mt-4">{{ $product->description }}</p>

                <div class="font-display text-3xl font-bold text-brand-600 mt-6">R$ {{ number_format($product->price, 2, ',', '.') }}</div>

                <div class="mt-2 text-sm">
                    @if ($product->isAvailable())
                        <x-badge variant="success">Em estoque · {{ $product->stock }} unidades</x-badge>
                    @else
                        <x-badge variant="danger">Produto esgotado</x-badge>
                    @endif
                </div>

                <div class="mt-6">
                    @auth
                        @if ($product->isAvailable())
                            <form method="POST" action="{{ route('cart.store') }}" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <x-text-input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20" />
                                <x-button variant="primary" size="lg" type="submit">
                                    Adicionar ao carrinho
                                </x-button>
                            </form>
                        @endif
                    @else
                        <x-button variant="primary" size="lg" :href="route('login')">
                            Entrar para comprar
                        </x-button>
                    @endauth
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h2 class="font-display text-lg font-semibold text-mocha-900 mb-4">Avaliações</h2>

                @auth
                    @if ($purchasedItem)
                        <form method="POST" action="{{ route('reviews.store', $product) }}" class="mb-6 space-y-3 pb-6 border-b border-cream-100">
                            @csrf
                            <div>
                                <label class="block text-sm text-mocha-600 mb-2">Sua nota</label>
                                <x-rating-input name="rating" :value="5" />
                            </div>
                            <div>
                                <textarea name="comment" rows="2" placeholder="Conte como foi sua experiência (opcional)"
                                          class="w-full rounded-lg border-mocha-200 focus:border-brand-500 focus:ring-brand-500"></textarea>
                            </div>
                            <x-button variant="primary" size="sm" type="submit">
                                Enviar avaliação
                            </x-button>
                        </form>
                    @endif
                @endauth

                @forelse ($product->reviews as $review)
                    <div class="border-t border-cream-100 first:border-t-0 py-3">
                        <x-rating-stars :rating="$review->rating" size="sm" />
                        <div class="text-sm font-medium text-mocha-800 mt-1">{{ $review->user->name }}</div>
                        @if ($review->comment)
                            <p class="text-sm text-mocha-500 mt-1">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <x-empty-state icon="⭐" title="Ainda não há avaliações" description="Seja o primeiro a avaliar este cupcake." />
                @endforelse
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
                <h2 class="font-display text-lg font-semibold text-mocha-900 mb-4">Comentários</h2>

                @auth
                    <form method="POST" action="{{ route('comments.store', $product) }}" class="mb-6 space-y-3 pb-6 border-b border-cream-100">
                        @csrf
                        <textarea name="body" rows="2" placeholder="Deixe uma pergunta ou comentário"
                                  class="w-full rounded-lg border-mocha-200 focus:border-brand-500 focus:ring-brand-500"></textarea>
                        <x-button variant="secondary" size="sm" type="submit">
                            Comentar
                        </x-button>
                    </form>
                @endauth

                @forelse ($product->comments as $comment)
                    <div class="border-t border-cream-100 first:border-t-0 py-3">
                        <div class="text-sm font-medium text-mocha-800">{{ $comment->user->name }}</div>
                        <p class="text-sm text-mocha-500 mt-1">{{ $comment->body }}</p>
                    </div>
                @empty
                    <x-empty-state icon="💬" title="Ainda não há comentários" description="Tire dúvidas ou deixe um recado sobre este cupcake." />
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
