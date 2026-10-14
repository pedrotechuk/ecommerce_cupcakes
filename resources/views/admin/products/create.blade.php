<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Novo Produto</h2>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form')

            <div class="mt-6 flex gap-3">
                <x-button variant="primary">Criar produto</x-button>
                <x-button variant="secondary" :href="route('admin.products.index')">Cancelar</x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
