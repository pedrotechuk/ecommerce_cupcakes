@props(['rating' => 0, 'size' => 'md'])

@php
    $rounded = (int) round($rating);
    $sizes = ['sm' => 'text-xs', 'md' => 'text-sm', 'lg' => 'text-lg'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center leading-none '.$sizeClass]) }} role="img" aria-label="{{ number_format($rating, 1) }} de 5 estrelas">
    <span class="text-amber-500">{{ str_repeat('★', $rounded) }}</span><span class="text-mocha-200">{{ str_repeat('★', 5 - $rounded) }}</span>
</span>
