@php
    $statusMessages = [
        'verification-link-sent' => 'Um novo link de verificação foi enviado para o seu e-mail.',
        'password-updated' => 'Sua senha foi atualizada com sucesso.',
        'profile-updated' => 'Perfil atualizado com sucesso.',
    ];
    $statusText = session('status') ? ($statusMessages[session('status')] ?? session('status')) : null;
@endphp

<div
    x-data="{
        toasts: [],
        add(type, message) {
            if (!message) return;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, type, message });
            setTimeout(() => this.remove(id), 5000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
    }"
    x-init="
        @if ($statusText) add('success', @js($statusText)); @endif
        @if ($errors->any()) add('error', @js($errors->first())); @endif
    "
    class="fixed top-4 right-4 left-4 sm:left-auto z-[100] flex flex-col gap-2 sm:w-full sm:max-w-sm pointer-events-none"
    aria-live="polite"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-start gap-3 rounded-xl shadow-lg px-4 py-3 text-sm border bg-white"
            :class="{
                'border-emerald-200': toast.type === 'success',
                'border-red-200': toast.type === 'error',
            }"
            role="status"
        >
            <span x-show="toast.type === 'success'" class="mt-0.5 text-emerald-600">✓</span>
            <span x-show="toast.type === 'error'" class="mt-0.5 text-red-600">⚠</span>
            <p class="flex-1 text-gray-700" x-text="toast.message"></p>
            <button type="button" @click="remove(toast.id)" class="text-gray-400 hover:text-gray-600" aria-label="Fechar notificação">✕</button>
        </div>
    </template>
</div>
