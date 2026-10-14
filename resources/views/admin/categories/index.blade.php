<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-mocha-900">Categorias</h2>
            <x-button variant="primary" size="sm" :href="route('admin.categories.create')">+ Nova categoria</x-button>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-mocha-400 border-b border-cream-200">
                    <th class="p-4">Nome</th>
                    <th class="p-4">Produtos</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b border-cream-100 hover:bg-cream-50 transition">
                        <td class="p-4 text-mocha-800">{{ $category->name }}</td>
                        <td class="p-4 text-mocha-500">{{ $category->products_count }}</td>
                        <td class="p-4">
                            <x-badge :variant="$category->is_active ? 'success' : 'neutral'">
                                {{ $category->is_active ? 'Ativa' : 'Inativa' }}
                            </x-badge>
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-600 hover:text-brand-800">Editar</a>
                            <x-confirm-dialog title="Remover esta categoria?" confirm-label="Sim, remover" class="inline-block ml-3">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Remover</button>
                                </form>
                            </x-confirm-dialog>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-mocha-400">Nenhuma categoria cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</x-admin-layout>
