<x-guest-layout>
    <div class="mb-4 text-sm text-mocha-500">
        Obrigado por se cadastrar! Antes de começar, você pode verificar seu e-mail clicando no link que acabamos de enviar? Se não recebeu o e-mail, teremos prazer em enviar outro.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-600">
            Um novo link de verificação foi enviado para o e-mail informado no cadastro.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar e-mail de verificação
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-mocha-500 hover:text-mocha-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>
