@props([
    'code' => null,
    'title' => 'Algo deu errado',
    'message' => 'Tente novamente em instantes.',
])
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Erro' }} - Cupcakes</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|fraunces:600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-cream-100">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 text-center">
            <a href="/" class="flex items-center gap-2 font-display text-2xl font-semibold text-brand-700 mb-8">
                <span aria-hidden="true">🧁</span> Cupcakes
            </a>

            <p class="font-display text-6xl sm:text-7xl font-semibold text-brand-600">{{ $code ?? '' }}</p>

            <h1 class="mt-4 text-xl sm:text-2xl font-semibold text-gray-800">{{ $title ?? 'Algo deu errado' }}</h1>
            <p class="mt-2 text-gray-500 max-w-md">{{ $message ?? 'Tente novamente em instantes.' }}</p>

            <a href="/" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-300 transition">
                Voltar para a loja
            </a>
        </div>
    </body>
</html>
