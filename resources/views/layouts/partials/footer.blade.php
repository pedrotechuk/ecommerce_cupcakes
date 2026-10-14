<footer class="bg-mocha-900 text-mocha-100 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 sm:grid-cols-3 gap-8">
        <div>
            <a href="{{ route('home') }}" class="font-display text-xl font-semibold text-white">🧁 Cupcakes</a>
            <p class="mt-2 text-sm text-mocha-200 max-w-xs">
                Cupcakes artesanais feitos com carinho, ingredientes selecionados e muito capricho em cada detalhe.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Loja</h3>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white">Início</a></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-white">Catálogo</a></li>
                @auth
                    <li><a href="{{ route('orders.index') }}" class="hover:text-white">Meus pedidos</a></li>
                @endauth
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Sobre</h3>
            <p class="mt-3 text-sm text-mocha-200 max-w-xs">
                Site desenvolvido para a diciplina de Projeto Integrador Transdiciplinar em Engenharia de Software II - Universidade Cruzeiro do Sul.
            </p>
        </div>
    </div>

    <div class="border-t border-mocha-700">
        <p class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs text-mocha-300">
            &copy; {{ now()->year }} Cupcakes Shop. Todos os direitos reservados.
        </p>
    </div>
</footer>
