<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Mis proyectos
            </h2>

            <a href="{{ route('projects.create') }}"
                class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                + Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($projects->isEmpty())
                <div class="rounded-xl bg-white p-10 text-center shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Todavía no tienes proyectos
                    </h3>

                    <p class="mt-2 text-gray-500">
                        Crea tu primer proyecto o espera a que te añadan a uno.
                    </p>

                    <a href="{{ route('projects.create') }}"
                        class="mt-5 inline-block rounded-lg bg-violet-600 px-5 py-2 text-white hover:bg-violet-700">
                        Crear proyecto
                    </a>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach ($projects as $project)
                        <div class="rounded-xl bg-white p-6 shadow-sm">

                            <div class="mb-3 flex items-center justify-between">
                                <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700">
                                    @if ($project->status === 'pending')
                                        Pendiente
                                    @elseif ($project->status === 'in_progress')
                                        En progreso
                                    @else
                                        Completado
                                    @endif
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $project->title }}
                            </h3>

                            <p class="mt-2 text-sm text-gray-600">
                                {{ Str::limit($project->description, 100) }}
                            </p>

                            <div class="mt-4 space-y-1 text-sm text-gray-500">
                                <p>
                                    Propietario: {{ $project->owner->name }}
                                </p>

                                <p>
                                    Fecha límite:
                                    {{ $project->deadline->format('d/m/Y') }}
                                </p>
                            </div>

                            <a href="{{ route('projects.show', $project) }}"
                                class="mt-5 inline-block font-semibold text-violet-600 hover:text-violet-800">
                                Ver proyecto →
                            </a>

                        </div>
                    @endforeach

                </div>

                <div>
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>