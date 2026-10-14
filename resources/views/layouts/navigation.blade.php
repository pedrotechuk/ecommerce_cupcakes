<nav x-data="{ open: false, scrolled: false }" x-init="scrolled = window.scrollY > 8" @scroll.window="scrolled = window.scrollY > 8"
     class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-cream-200 transition-shadow duration-200"
     :class="scrolled ? 'shadow-md' : 'shadow-none'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 gap-4">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-2 font-display text-xl font-bold text-brand-600">
                    <span aria-hidden="true">🧁</span> Cupcakes
                </a>

                <div class="hidden lg:flex lg:space-x-8">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Início') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Catálogo') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            {{ __('Meus Pedidos') }}
                        </x-nav-link>
                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('Painel Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-sm">
                <label for="nav-search" class="sr-only">Buscar cupcakes</label>
                <div class="relative w-full">
                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-mocha-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.65 4.65a7.5 7.5 0 0011.999 11.999z" />
                    </svg>
                    <input id="nav-search" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar cupcakes..."
                           class="w-full rounded-full border-mocha-200 bg-cream-50 pl-9 pr-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500 focus:bg-white transition">
                </div>
            </form>

            <div class="hidden lg:flex lg:items-center lg:gap-4">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-mocha-600 hover:text-brand-600 transition-colors">
                        <span aria-hidden="true">🛒</span> {{ __('Carrinho') }}
                        @php($cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0)
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-2 inline-flex items-center justify-center rounded-full bg-brand-600 text-white text-[11px] font-semibold w-5 h-5">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg text-mocha-700 hover:bg-cream-100 focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Meu Perfil') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('addresses.index')">{{ __('Meus Endereços') }}</x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <x-nav-link :href="route('login')">{{ __('Entrar') }}</x-nav-link>
                    <x-nav-link :href="route('register')">{{ __('Cadastrar') }}</x-nav-link>
                @endauth
            </div>

            <div class="flex items-center gap-1 lg:hidden">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center p-2 text-mocha-600" aria-label="Carrinho">
                        <span aria-hidden="true" class="text-xl">🛒</span>
                        @php($cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0)
                        @if ($cartCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center rounded-full bg-brand-600 text-white text-[11px] font-semibold w-5 h-5">{{ $cartCount }}</span>
                        @endif
                    </a>
                @endauth
                <button @click="open = true" class="inline-flex items-center justify-center p-2 rounded-lg text-mocha-500 hover:bg-cream-100 focus:outline-none transition" aria-label="Abrir menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 lg:hidden" x-on:keydown.escape.window="open = false">
        <div class="fixed inset-0 bg-mocha-900 opacity-60"
             x-show="open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-60"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-60" x-transition:leave-end="opacity-0"
             @click="open = false"></div>

        <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-xl flex flex-col"
             x-show="open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="flex items-center justify-between px-4 h-16 border-b border-cream-200 shrink-0">
                <span class="font-display text-lg font-bold text-brand-600">🧁 Cupcakes</span>
                <button @click="open = false" class="p-2 rounded-lg text-mocha-500 hover:bg-cream-100" aria-label="Fechar menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('products.index') }}" method="GET" class="p-4 border-b border-cream-100 shrink-0">
                <label for="nav-search-mobile" class="sr-only">Buscar cupcakes</label>
                <input id="nav-search-mobile" type="search" name="search" value="{{ request('search') }}" placeholder="Buscar cupcakes..."
                       class="w-full rounded-full border-mocha-200 bg-cream-50 text-sm focus:border-brand-500 focus:ring-brand-500">
            </form>

            <div class="flex-1 overflow-y-auto py-2">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('Início') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">{{ __('Catálogo') }}</x-responsive-nav-link>
                @auth
                    <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">{{ __('Carrinho') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">{{ __('Meus Pedidos') }}</x-responsive-nav-link>
                    @if (auth()->user()->isAdmin())
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">{{ __('Painel Admin') }}</x-responsive-nav-link>
                    @endif
                @else
                    <x-responsive-nav-link :href="route('login')">{{ __('Entrar') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Cadastrar') }}</x-responsive-nav-link>
                @endauth
            </div>

            @auth
                <div class="border-t border-cream-200 py-4 px-4 shrink-0">
                    <div class="font-medium text-base text-mocha-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-mocha-400">{{ Auth::user()->email }}</div>

                    <div class="mt-3 -mx-4 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">{{ __('Meu Perfil') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('addresses.index')">{{ __('Meus Endereços') }}</x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Sair') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
