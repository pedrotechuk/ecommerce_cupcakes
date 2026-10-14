@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])

@php
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $variants = [
        'primary' => 'bg-brand-600 text-white border border-transparent hover:bg-brand-700 active:bg-brand-800 focus-visible:ring-brand-500',
        'secondary' => 'bg-white text-mocha-800 border border-mocha-200 hover:bg-cream-100 active:bg-cream-200 focus-visible:ring-brand-500',
        'danger' => 'bg-red-600 text-white border border-transparent hover:bg-red-500 active:bg-red-700 focus-visible:ring-red-500',
        'ghost' => 'bg-transparent text-mocha-700 border border-transparent shadow-none hover:bg-mocha-50 active:bg-mocha-100 focus-visible:ring-brand-500',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold tracking-wide shadow-sm transition duration-150 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed '
        .($sizes[$size] ?? $sizes['md']).' '
        .($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
