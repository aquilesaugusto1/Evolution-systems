<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/webp" href="{{ asset('images/favicon.webp') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        @stack('styles')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full">
        <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
            
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col">
                @include('layouts.navigation')

                <main class="flex-1">
                    <div class="py-8 px-4 sm:px-6 lg:px-8">
                         @if (session('success'))
                              <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg" role="alert">
                                  <p class="font-bold">Sucesso</p>
                                  <p>{{ session('success') }}</p>
                              </div>
                          @endif
                          @if ($errors->any())
                              <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg" role="alert">
                                  <p class="font-bold">Erro de Validação</p>
                                  <ul class="mt-1 list-disc list-inside">
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        @stack('scripts')
    </body>
</html>
