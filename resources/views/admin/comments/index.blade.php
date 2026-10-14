<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-display font-semibold text-xl text-mocha-900">Moderação de Comentários</h2>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-cream-200 divide-y divide-cream-100">
        @forelse ($comments as $comment)
            <div class="p-4 flex items-start justify-between gap-4">
                <div>
                    <div class="text-sm font-medium text-mocha-800">
                        {{ $comment->user->name }}
                        <span class="text-mocha-400 font-normal">em</span>
                        <a href="{{ route('products.show', $comment->product) }}" class="text-brand-600 hover:text-brand-800">{{ $comment->product->name }}</a>
                        @if ($comment->is_hidden)
                            <x-badge variant="neutral" class="ml-2">Oculto</x-badge>
                        @endif
                    </div>
                    <p class="text-sm text-mocha-600 mt-1">{{ $comment->body }}</p>
                    <div class="text-xs text-mocha-400 mt-1">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <form method="POST" action="{{ route('admin.comments.toggle', $comment) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-sm text-brand-600 hover:text-brand-800">
                            {{ $comment->is_hidden ? 'Reexibir' : 'Ocultar' }}
                        </button>
                    </form>
                    <x-confirm-dialog title="Remover este comentário?" confirm-label="Sim, remover">
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-500 hover:text-red-700">Remover</button>
                        </form>
                    </x-confirm-dialog>
                </div>
            </div>
        @empty
            <div class="p-4">
                <x-empty-state icon="💬" title="Nenhum comentário registrado" />
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</x-admin-layout>
