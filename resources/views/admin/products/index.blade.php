<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-mocha-900">Produtos</h2>
            <x-button variant="primary" size="sm" :href="route('admin.products.create')">+ Novo produto</x-button>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-mocha-400 border-b border-cream-200">
                    <th class="p-4">Produto</th>
                    <th class="p-4">Categoria</th>
                    <th class="p-4">Preço</th>
                    <th class="p-4">Estoque</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b border-cream-100 hover:bg-cream-50 transition">
                        <td class="p-4 flex items-center gap-3 text-mocha-800">
                            <img src="{{ $product->image_url }}" class="w-10 h-10 object-cover rounded-lg">
                            {{ $product->name }}
                        </td>
                        <td class="p-4 text-mocha-500">{{ $product->category->name }}</td>
                        <td class="p-4 text-mocha-500">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td class="p-4 text-mocha-500">{{ $product->stock }}</td>
                        <td class="p-4">
                            <x-badge :variant="$product->is_active ? 'success' : 'neutral'">
                                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                            </x-badge>
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-brand-600 hover:text-brand-800">Editar</a>
                            <x-confirm-dialog title="Remover este produto?" confirm-label="Sim, remover" class="inline-block ml-3">
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Remover</button>
                                </form>
                            </x-confirm-dialog>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-mocha-400">Nenhum produto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</x-admin-layout>
