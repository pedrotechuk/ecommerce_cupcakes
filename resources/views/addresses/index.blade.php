<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Meus Endereços</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
            @forelse ($addresses as $address)
                <div x-data="{ editing: false }" class="p-4">
                    <div x-show="! editing" class="flex items-center justify-between gap-4">
                        <div>
                            <div class="font-medium text-mocha-800 flex items-center gap-2">
                                {{ $address->street }}, {{ $address->number }}
                                @if ($address->is_default)
                                    <x-badge variant="brand">Padrão</x-badge>
                                @endif
                            </div>
                            <div class="text-sm text-mocha-400">
                                {{ $address->complement ? $address->complement . ' — ' : '' }}{{ $address->city }}
                                {{ $address->zip_code ? '— CEP ' . $address->zip_code : '' }}
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <button type="button" @click="editing = true" class="text-sm text-brand-600 hover:text-brand-800">Editar</button>
                            <x-confirm-dialog title="Excluir endereço?" message="Essa ação não pode ser desfeita." confirm-label="Sim, excluir" class="inline-block">
                                <form method="POST" action="{{ route('addresses.destroy', $address) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">Excluir</button>
                                </form>
                            </x-confirm-dialog>
                        </div>
                    </div>

                    <form x-show="editing" method="POST" action="{{ route('addresses.update', $address) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                        @csrf
                        @method('PATCH')
                        <x-text-input type="text" name="street" value="{{ $address->street }}" placeholder="Rua" />
                        <x-text-input type="text" name="number" value="{{ $address->number }}" placeholder="Número" />
                        <x-text-input type="text" name="complement" value="{{ $address->complement }}" placeholder="Complemento (opcional)" />
                        <x-text-input type="text" name="city" value="{{ $address->city }}" placeholder="Cidade" />
                        <x-text-input type="text" name="zip_code" value="{{ $address->zip_code }}" placeholder="CEP (opcional)" />
                        <label class="flex items-center gap-2 text-sm text-mocha-600">
                            <input type="checkbox" name="is_default" value="1" class="rounded text-brand-600 focus:ring-brand-500" @checked($address->is_default)>
                            Definir como padrão
                        </label>
                        <div class="sm:col-span-2 flex gap-3">
                            <x-button variant="primary" size="sm" type="submit">Salvar</x-button>
                            <button type="button" @click="editing = false" class="text-sm text-mocha-500 hover:text-mocha-700">Cancelar</button>
                        </div>
                    </form>
                </div>
            @empty
                <x-empty-state icon="📍" title="Você ainda não cadastrou nenhum endereço" description="Adicione um endereço para receber seus pedidos." />
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-cream-200 p-6">
            <h3 class="font-semibold text-mocha-900 mb-4">Adicionar novo endereço</h3>
            <form method="POST" action="{{ route('addresses.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @csrf
                <x-text-input type="text" name="street" placeholder="Rua" required />
                <x-text-input type="text" name="number" placeholder="Número" required />
                <x-text-input type="text" name="complement" placeholder="Complemento (opcional)" />
                <x-text-input type="text" name="city" placeholder="Cidade" required />
                <x-text-input type="text" name="zip_code" placeholder="CEP (opcional)" />
                <label class="flex items-center gap-2 text-sm text-mocha-600">
                    <input type="checkbox" name="is_default" value="1" class="rounded text-brand-600 focus:ring-brand-500">
                    Definir como padrão
                </label>
                <div class="sm:col-span-2">
                    <x-button variant="primary" size="sm" type="submit">Adicionar</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
