<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Admin - {{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|fraunces:500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-cream-100">
        <x-toast-container />

        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">
            <!-- Mobile topbar -->
            <div class="md:hidden flex items-center justify-between bg-mocha-900 text-white px-4 py-3">
                <span class="font-display font-semibold">🧁 Cupcakes Admin</span>
                <button @click="sidebarOpen = true" class="p-2 -mr-2" aria-label="Abrir menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>

            <!-- Mobile drawer backdrop -->
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/40 z-30 md:hidden"
                style="display: none;"
            ></div>

            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
                class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-mocha-900 text-mocha-100 shrink-0 transform transition-transform duration-200 ease-in-out md:transform-none"
            >
                <div class="p-4 flex items-center justify-between">
                    <span class="text-xl font-display font-semibold text-white">🧁 Cupcakes Admin</span>
                    <button @click="sidebarOpen = false" class="md:hidden p-1 text-mocha-300 hover:text-white" aria-label="Fechar menu">✕</button>
                </div>
                <nav class="px-2 pb-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Produtos</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Categorias</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Pedidos</a>
                    <a href="{{ route('admin.sales.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.sales.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Vendas</a>
                    <a href="{{ route('admin.coupons.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.coupons.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Cupons</a>
                    <a href="{{ route('admin.comments.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.comments.*') ? 'bg-mocha-700 text-white' : 'text-mocha-100 hover:bg-mocha-800' }}">Comentários</a>
                    <div class="border-t border-mocha-700 my-2"></div>
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-mocha-100 hover:bg-mocha-800">Ver loja</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-mocha-100 hover:bg-mocha-800">Sair</button>
                    </form>
                </nav>
            </aside>

            <div class="flex-1 min-w-0">
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
