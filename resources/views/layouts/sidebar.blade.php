<div x-show="sidebarOpen" class="lg:hidden fixed inset-0 z-30 bg-black/30" @click="sidebarOpen = false" x-cloak></div>

<div
    class="bg-slate-900 text-slate-300 w-64 flex-shrink-0 flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
    :class="{'translate-x-0': sidebarOpen}"
>
    <div class="flex items-center justify-center h-16 bg-slate-950 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-slate-800 flex-shrink-0">
                <img class="h-6 w-6" src="{{ asset('images/favicon.webp') }}" alt="Logo da Progmud">
            </div>
            <span class="text-2xl font-bold text-slate-200">Progmud</span>
        </a>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" /></svg>
            Início
        </a>
        <a href="{{ route('agendas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('agendas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008z" /></svg>
            Agendas
        </a>
        <a href="{{ route('apontamentos.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('apontamentos.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Apontamentos
        </a>
        <a href="{{ route('relatorios.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('relatorios.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 1.5m1-1.5l1 1.5m0 0l.5 1.5m.5-1.5l-1.5-2.25m1.5 2.25l1.5-2.25m0 0l1.5 2.25m-1.5-2.25l-1.5 2.25m-7.5 0h7.5" /></svg>
            Relatórios
        </a>
        <a href="{{ route('sugestoes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('sugestoes.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.311a15.045 15.045 0 01-7.5 0C4.508 17.67 2.25 15.443 2.25 12.75c0-2.692 2.258-4.92 5.04-5.233.95-.112 1.933-.167 2.96-.167s2.01.055 2.96.167c2.782.313 5.04 2.54 5.04 5.233 0 2.693-2.258 4.92-5.04 5.233z" /></svg>
            Sugestões
        </a>
        <hr class="my-4 border-slate-700">
        <p class="px-4 text-xs font-semibold uppercase text-slate-500">Gestão</p>
        
        @if(in_array(auth()->user()->funcao, ['admin', 'coordenador_operacoes']))
            <a href="{{ route('faturamento.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('faturamento.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.785c0 1.135.845 2.098 1.976 2.192.341.026.682.037 1.024.037s.683-.011 1.024-.037c1.131-.094 1.976-1.057 1.976-2.192v-2.785c0-1.135-.845-2.098-1.976-2.192A48.424 48.424 0 0012 8.25zM12 15V12m0 3a2.25 2.25 0 110-4.5 2.25 2.25 0 010 4.5z" /></svg>
                Faturamento
            </a>
        @endif
        
        @can('viewAprovacoes', App\Models\Apontamento::class)
            <a href="{{ route('aprovacoes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('aprovacoes.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Aprovações
            </a>
        @endcan
        @if(in_array(auth()->user()->funcao, ['admin', 'coordenador_operacoes', 'coordenador_tecnico', 'techlead']))
            <a href="{{ route('email.agendas.create') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('email.agendas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                Reenviar Agendas
            </a>
        @endif
        <hr class="my-4 border-slate-700">
        <p class="px-4 text-xs font-semibold uppercase text-slate-500">Cadastros</p>
        @can('viewAny', App\Models\User::class)
            <a href="{{ route('colaboradores.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('colaboradores.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-2.305c.395-.44-.22-1.078-.682-1.078h-4.042a1.125 1.125 0 01-1.125-1.125v-4.042A1.125 1.125 0 019.875 8.25c.662 0 1.185.523 1.185 1.185v4.042a1.125 1.125 0 001.125 1.125h4.042zM18 12.375a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                Colaboradores
            </a>
        @endcan
        @can('viewAny', App\Models\EmpresaParceira::class)
            <a href="{{ route('empresas.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('empresas.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6h1.5m-1.5 3h1.5m-1.5 3h1.5M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                Clientes
            </a>
        @endcan
        @can('viewAny', App\Models\Contrato::class)
            <a href="{{ route('contratos.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('contratos.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Contratos
            </a>
        @endcan
    </nav>
</div>