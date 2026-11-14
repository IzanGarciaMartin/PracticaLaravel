<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-[#713DE0]">
                TU ESPACIO DE TRABAJO
            </p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#252238]">
                Dashboard
            </h1>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <section
                class="relative isolate overflow-hidden rounded-3xl p-7 text-white shadow-lg shadow-purple-200/40 sm:p-10"
                style="background: linear-gradient(120deg, #713DE0 0%, #888CE4 100%);">
                <div class="relative z-10 max-w-2xl">
                    <p class="text-sm font-bold tracking-wide text-purple-100">
                        BIENVENIDO A TASKORA
                    </p>

                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">
                        ¡Hola, {{ Auth::user()->name }}! 👋
                    </h2>

                    <p class="mt-3 max-w-lg text-sm leading-relaxed text-white/90 sm:text-base">
                        Este es tu espacio de trabajo. Organiza tus proyectos,
                        colabora con otras personas y sigue avanzando hacia tus objetivos.
                    </p>

                    <a href="{{ route('projects.create') }}"
                        class="mt-7 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-[#713DE0] shadow-md transition hover:-translate-y-0.5 hover:bg-purple-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Crear proyecto
                    </a>
                </div>

                {{-- Decorative elements --}}
                <div
                    class="pointer-events-none absolute -right-12 -top-20 h-72 w-72 rounded-full border-[35px] border-white/10">
                </div>
                <div class="pointer-events-none absolute -bottom-32 right-28 h-64 w-64 rounded-full bg-white/10"></div>
            </section>

            {{-- Statistics --}}
            <section>
                <div class="mb-5">
                    <h2 class="text-lg font-bold text-[#252238]">
                        Tu actividad
                    </h2>
                    <p class="mt-1 text-sm text-[#777386]">
                        Un resumen de tu espacio de trabajo.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Owned projects --}}
                    <div class="rounded-2xl border border-[#EAE7F0] bg-white p-6 shadow-sm transition hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="16" rx="2" />
                                    <path d="M7 8h10M7 12h6" />
                                </svg>
                            </div>

                            <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-bold text-[#713DE0]">
                                Propietario
                            </span>
                        </div>

                        <div class="mt-5">
                            <p class="text-sm font-bold text-[#777386]">
                                Proyectos propios
                            </p>

                            <p class="mt-1 text-4xl font-extrabold tracking-tight text-[#252238] tabular-nums">
                                {{ $ownedProjectsCount }}
                            </p>

                            <p class="mt-2 text-sm text-[#777386]">
                                Proyectos que has creado y gestionas.
                            </p>
                        </div>
                    </div>

                    {{-- Shared projects --}}
                    <div class="rounded-2xl border border-[#EAE7F0] bg-white p-6 shadow-sm transition hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-[#888CE4]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>

                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-[#888CE4]">
                                Colaborador
                            </span>
                        </div>

                        <div class="mt-5">
                            <p class="text-sm font-bold text-[#777386]">
                                Proyectos compartidos
                            </p>

                            <p class="mt-1 text-4xl font-extrabold tracking-tight text-[#252238] tabular-nums">
                                {{ $collaborationsCount }}
                            </p>

                            <p class="mt-2 text-sm text-[#777386]">
                                Proyectos en los que participas con otras personas.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            {{-- Recent projects --}}
            <section>
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-[#252238]">
                            Tus proyectos recientes
                        </h2>
                        <p class="mt-1 text-sm text-[#777386]">
                            Accede rápidamente a tus últimos proyectos.
                        </p>
                    </div>

                    <a href="{{ route('projects.index') }}"
                        class="text-sm font-bold text-[#713DE0] transition hover:text-[#5829BD]">
                        Ver todos →
                    </a>
                </div>

                @forelse ($ownedProjects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                        class="mb-3 flex flex-col gap-4 rounded-2xl border border-[#EAE7F0] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#D6B5FE] hover:shadow-md sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex min-w-0 items-center gap-4">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7h7l2 2h9v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <path d="M3 7V5a2 2 0 0 1 2-2h5l2 2" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <h3 class="truncate font-bold text-[#252238]">
                                    {{ $project->title }}
                                </h3>

                                <p class="mt-1 line-clamp-2 text-sm text-[#777386]">
                                    {{ $project->description ?: 'Sin descripción disponible.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center justify-between gap-4 sm:justify-end">
                            @if ($project->deadline)
                                <span class="text-xs font-medium text-[#777386]">
                                    Fecha límite: {{ $project->deadline->format('d/m/Y') }}
                                </span>
                            @endif

                            <span class="rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-[#713DE0]">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="rounded-2xl border border-dashed border-[#D6B5FE] bg-white px-6 py-12 text-center">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-[#713DE0]">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h7l2 2h9v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                <path d="M12 12v5M9.5 14.5h5" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-[#252238]">
                            Todavía no tienes proyectos
                        </h3>

                        <p class="mx-auto mt-2 max-w-sm text-sm text-[#777386]">
                            ¡Este es el momento perfecto para empezar!
                            Crea tu primer proyecto y organiza tus ideas.
                        </p>

                        <a href="{{ route('projects.create') }}"
                            class="mt-5 inline-flex items-center rounded-xl bg-[#713DE0] px-5 py-3 text-sm font-bold text-white shadow-md shadow-purple-200 transition hover:bg-[#602FCB]">
                            Crear mi primer proyecto
                        </a>
                    </div>
                @endforelse
            </section>

            {{-- Quick links --}}
            <section class="rounded-2xl border border-[#EAE7F0] bg-white p-6 shadow-sm sm:p-7">
                <h2 class="font-bold text-[#252238]">
                    Accesos rápidos
                </h2>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('projects.index') }}"
                        class="flex items-center gap-3 rounded-xl border border-[#EAE7F0] p-4 transition hover:border-[#D6B5FE] hover:bg-purple-50/50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-[#252238]">
                                Explorar proyectos
                            </p>
                            <p class="mt-0.5 text-xs text-[#777386]">
                                Consulta tu espacio de trabajo.
                            </p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-xl border border-[#EAE7F0] p-4 transition hover:border-[#D6B5FE] hover:bg-purple-50/50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M5 21a7 7 0 0 1 14 0" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-[#252238]">
                                Mi perfil
                            </p>
                            <p class="mt-0.5 text-xs text-[#777386]">
                                Gestiona tu cuenta y seguridad.
                            </p>
                        </div>
                    </a>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>