@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-mocha-800']) }}>
    {{ $value ?? $slot }}
</label>
