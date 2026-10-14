@props([
    'title' => 'Tem certeza?',
    'message' => 'Essa ação não pode ser desfeita.',
    'confirmLabel' => 'Confirmar',
    'cancelLabel' => 'Cancelar',
])

<div x-data="{ open: false }" {{ $attributes }}>
    <div x-ref="wrapper" x-on:submit.prevent="open = true">
        {{ $slot }}
    </div>

    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0" x-on:keydown.escape.window="open = false">
        <div class="fixed inset-0 bg-mocha-900 opacity-60"
             x-show="open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-60"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-60" x-transition:leave-end="opacity-0"
             x-on:click="open = false"></div>

        <div class="relative mb-6 bg-white rounded-2xl shadow-xl transform transition-all sm:max-w-md sm:mx-auto sm:mt-24 p-6"
             x-show="open"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
            <h3 class="font-display text-lg font-semibold text-mocha-900">{{ $title }}</h3>
            <p class="text-sm text-mocha-500 mt-2">{{ $message }}</p>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="open = false">{{ $cancelLabel }}</x-secondary-button>
                <x-button variant="danger" type="button" x-on:click="open = false; $refs.wrapper.querySelector('form').submit();">
                    {{ $confirmLabel }}
                </x-button>
            </div>
        </div>
    </div>
</div>
