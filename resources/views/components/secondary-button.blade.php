<x-button variant="secondary" :type="$attributes->get('type', 'button')" {{ $attributes->except('type') }}>
    {{ $slot }}
</x-button>
