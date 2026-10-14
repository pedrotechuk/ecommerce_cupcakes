<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display font-semibold text-xl text-mocha-900">Cupons</h2>
            <x-button variant="primary" size="sm" :href="route('admin.coupons.create')">+ Novo cupom</x-button>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-mocha-400 border-b border-cream-200">
                    <th class="p-4">Código</th>
                    <th class="p-4">Tipo</th>
                    <th class="p-4">Valor</th>
                    <th class="p-4">Uso</th>
                    <th class="p-4">Expira em</th>
                    <th class="p-4">Status</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr class="border-b border-cream-100 hover:bg-cream-50 transition">
                        <td class="p-4 font-medium text-mocha-800">{{ $coupon->code }}</td>
                        <td class="p-4 text-mocha-500">{{ $coupon->type === 'percentage' ? 'Percentual' : 'Fixo' }}</td>
                        <td class="p-4 text-mocha-500">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'R$ ' . number_format($coupon->value, 2, ',', '.') }}
                        </td>
                        <td class="p-4 text-mocha-500">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                        <td class="p-4 text-mocha-500">{{ $coupon->expires_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="p-4">
                            <x-badge :variant="$coupon->is_active ? 'success' : 'neutral'">
                                {{ $coupon->is_active ? 'Ativo' : 'Inativo' }}
                            </x-badge>
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-brand-600 hover:text-brand-800">Editar</a>
                            <x-confirm-dialog title="Remover este cupom?" confirm-label="Sim, remover" class="inline-block ml-3">
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Remover</button>
                                </form>
                            </x-confirm-dialog>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-mocha-400">Nenhum cupom cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $coupons->links() }}
    </div>
</x-admin-layout>
