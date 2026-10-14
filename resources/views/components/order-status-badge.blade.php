@props(['status'])

@php
    $variantMap = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'preparing' => 'brand',
        'out_for_delivery' => 'brand',
        'delivered' => 'success',
        'cancelled' => 'danger',
    ];

    $label = \App\Models\Order::STATUS_LABELS[$status] ?? $status;
    $variant = $variantMap[$status] ?? 'neutral';
@endphp

<x-badge :variant="$variant" {{ $attributes }}>
    {{ $label }}
</x-badge>
