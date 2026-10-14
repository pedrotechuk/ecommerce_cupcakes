<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Nova Categoria</h2>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @include('admin.categories._form')

            <div class="mt-6 flex gap-3">
                <x-button variant="primary">Criar categoria</x-button>
                <x-button variant="secondary" :href="route('admin.categories.index')">Cancelar</x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
