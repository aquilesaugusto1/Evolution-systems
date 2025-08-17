<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portal Progmud</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <div class="bg-slate-900">
            <header class="absolute inset-x-0 top-0 z-50">
                <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <a href="#" class="-m-1.5 p-1.5 flex items-center space-x-3">
                            <img class="h-8 w-auto" src="{{ asset('images/favicon.webp') }}" alt="Logo da Progmud">
                            <span class="text-2xl font-bold text-white">Portal Progmud</span>
                        </a>
                    </div>
                    <div class="lg:flex lg:flex-1 lg:justify-end">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-white hover:text-indigo-400">Entrar no sistema <span aria-hidden="true">&rarr;</span></a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-white hover:text-indigo-400">Login <span aria-hidden="true">&rarr;</span></a>
                        @endauth
                    </div>
                </nav>
            </header>

            <main class="relative isolate px-6 pt-14 lg:px-8">
                <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#8085ff] to-[#4f46e5] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
                </div>
                <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">Gestão de Consultoria simplificada.</h1>
                        <p class="mt-6 text-lg leading-8 text-slate-300">Organize os seus projetos, controle as horas dos seus consultores e mantenha uma comunicação transparente com os seus clientes. Tudo numa plataforma centralizada e eficiente.</p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            @auth
                                <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Ir para o Painel</a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Login</a>
                            @endauth
                            <a href="#sobre" class="text-sm font-semibold leading-6 text-white">Saber mais <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <section id="sobre" class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <h2 class="text-base font-semibold leading-7 text-indigo-600">O que é o Portal Progmud?</h2>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Uma Ferramenta Completa para a Sua Operação</p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Nascido da necessidade de clareza e controle, o Portal Progmud foi projetado para centralizar a gestão de projetos de consultoria. Desde a alocação de Tech Leads e Consultores até ao apontamento detalhado de horas e o envio de relatórios, o nosso objetivo é transformar a complexidade em eficiência.</p>
                </div>
            </div>
        </section>

        <section id="equipa" class="bg-slate-900 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">A Equipe por De trás do Código</h2>
                    <p class="mt-6 text-lg leading-8 text-slate-300">Uma dupla jovem e dinâmica, unida pela paixão por criar soluções tecnológicas que resolvem problemas reais.</p>
                </div>
                <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none">
                    <div class="flex flex-col items-start">
                        <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('images/foto-aquiles.jpg') }}" alt="Foto de Aquiles Morato">
                        <h3 class="mt-6 text-lg font-semibold leading-8 text-white">Aquiles Morato</h3>
                        <p class="text-base leading-7 text-indigo-400">Desenvolvedor Full-Stack</p>
                        <p class="mt-4 text-base leading-7 text-slate-300">Com apenas 18 anos, Aquiles é o arquiteto do sistema. Ele transforma lógicas de negócio complexas em funcionalidades robustas e eficientes, garantindo que a fundação do Portal Progmud seja sólida, segura e perfeitamente escalável para o futuro.</p>
                    </div>
                    <div class="flex flex-col items-start">
                        <img class="h-24 w-24 rounded-full object-cover" src="{{ asset('images/foto-luca.jpg') }}" alt="Foto de Luca Morato">
                        <h3 class="mt-6 text-lg font-semibold leading-8 text-white">Luca Morato</h3>
                        <p class="text-base leading-7 text-fuchsia-400">Front-end & UX Designer</p>
                        <p class="mt-4 text-base leading-7 text-slate-300">Também com 18 anos, Luca é o mestre da experiência do utilizador. O seu foco incansável é criar um fluxo de trabalho intuitivo e um design limpo, garantindo que o poder do Portal Progmud seja não só acessível, mas também agradável de usar no dia a dia.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-slate-950">
            <div class="mx-auto max-w-7xl overflow-hidden px-6 py-12 lg:px-8">
                <p class="text-center text-xs leading-5 text-slate-400">&copy; {{ date('Y') }} Portal Progmud. Todos os direitos reservados.</p>
            </div>
        </footer>
    </body>
</html>