<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Atualize as informações do seu perfil e endereço de e-mail.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nome" value="Nome" />
            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $user->nome)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
        </div>

        <div>
            <x-input-label for="sobrenome" value="Sobrenome" />
            <x-text-input id="sobrenome" name="sobrenome" type="text" class="mt-1 block w-full" :value="old('sobrenome', $user->sobrenome)" autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('sobrenome')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Seu endereço de e-mail não foi verificado.
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Clique aqui para reenviar o e-mail de verificação.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Um novo link de verificação foi enviado para o seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>
        
        <div>
            <x-input-label for="foto_url" value="URL da Foto" />
            <x-text-input id="foto_url" name="foto_url" type="url" class="mt-1 block w-full" :value="old('foto_url', $user->foto_url)" />
            <x-input-error class="mt-2" :messages="$errors->get('foto_url')" />
        </div>

        <div>
            <x-input-label for="bio" value="Bio" />
            <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="linkedin" value="LinkedIn URL" />
            <x-text-input id="linkedin" name="redes_sociais[linkedin]" type="url" class="mt-1 block w-full" :value="old('redes_sociais.linkedin', $user->redes_sociais['linkedin'] ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('redes_sociais.linkedin')" />
        </div>

        <div>
            <x-input-label for="github" value="GitHub URL" />
            <x-text-input id="github" name="redes_sociais[github]" type="url" class="mt-1 block w-full" :value="old('redes_sociais.github', $user->redes_sociais['github'] ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('redes_sociais.github')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>
