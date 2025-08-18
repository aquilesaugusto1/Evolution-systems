<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Minhas Habilidades
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Atualize suas competências e níveis de proficiência.
        </p>
    </header>

    <form method="post" action="{{ route('profile.skills.update') }}" class="mt-6">
        @csrf
        @method('patch')

        <div class="space-y-6">
            @forelse($skills as $categoria => $skillsCategoria)
                <div>
                    <h3 class="text-md font-semibold text-gray-800 border-b pb-2 mb-3">{{ $categoria }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                        @foreach($skillsCategoria as $skill)
                            <div>
                                <label for="skill-{{ $skill->id }}" class="block text-sm font-medium text-gray-700">{{ $skill->nome }}</label>
                                <select name="skills[{{ $skill->id }}]" id="skill-{{ $skill->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Não possuo</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ ($userSkills[$skill->id] ?? 0) == $i ? 'selected' : '' }}>
                                            Nível {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nenhuma habilidade cadastrada no sistema. Por favor, contate um administrador.</p>
            @endforelse
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>Salvar Habilidades</x-primary-button>

            @if (session('status') === 'skills-updated')
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
