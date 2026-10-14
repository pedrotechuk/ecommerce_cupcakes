@props(['icon' => '🧁', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-12 px-6']) }}>
    <div class="text-5xl mb-4" aria-hidden="true">{{ $icon }}</div>
    <h3 class="font-display text-lg font-semibold text-mocha-800">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-mocha-500 mt-1 max-w-sm">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-6">{{ $action }}</div>
    @endisset
</div>
