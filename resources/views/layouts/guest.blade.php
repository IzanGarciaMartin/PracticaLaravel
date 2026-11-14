<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Taskora') }}</title>

    <!-- Tipografía -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen antialiased">

    <main class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden px-5 py-12">

        <!-- Elementos decorativos -->
        <div class="pointer-events-none absolute -top-32 -right-24 h-96 w-96 rounded-full bg-purple-200/50 blur-3xl">
        </div>
        <div class="pointer-events-none absolute -bottom-40 -left-24 h-96 w-96 rounded-full bg-violet-200/50 blur-3xl">
        </div>

        <!-- Contenido -->
        <div class="relative z-10 w-full max-w-md">

            <!-- Logo -->
            <a href="/" class="mb-8 flex flex-col items-center gap-3 text-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#713DE0] shadow-lg shadow-purple-300/50">
                    <svg class="h-9 w-9 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6" />
                    </svg>
                </div>

                <span class="text-3xl font-extrabold tracking-tight text-[#252238]">
                    taskora<span class="text-[#713DE0]">.</span>
                </span>

                <span class="text-sm font-medium text-[#777386]">
                    Organiza tus ideas. Consigue tus objetivos.
                </span>
            </a>

            <!-- Tarjeta de autenticación -->
            <section
                class="rounded-3xl border border-white/80 bg-white/90 p-7 shadow-xl shadow-purple-900/5 backdrop-blur-sm sm:p-9">
                {{ $slot }}
            </section>

            <!-- Pie -->
            <p class="mt-8 text-center text-xs font-medium text-[#777386]">
                © {{ date('Y') }} Taskora. Todos los derechos reservados.
            </p>

        </div>
    </main>

</body>

</html>