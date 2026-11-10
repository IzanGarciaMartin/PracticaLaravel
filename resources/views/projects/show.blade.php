<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Detalles del proyecto
            </h2>

            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-violet-600 hover:text-violet-800">
                ← Volver a proyectos
            </a>
        </div>
    </x-slot>

    @php
        $isOwner = $project->user_id === auth()->id();

        $isAdmin = $project->members
            ->contains(function ($member) {
                return $member->id === auth()->id()
                    && $member->pivot->role === 'admin';
            });

        $canManageMembers = $isOwner || $isAdmin;
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Información general del proyecto -->
            <section class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">

                    <div>
                        <div class="mb-3">
                            @if ($project->status === 'pending')
                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                    Pendiente
                                </span>
                            @elseif ($project->status === 'in_progress')
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                    En progreso
                                </span>
                            @else
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                    Completado
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $project->title }}
                        </h1>

                        <p class="mt-2 text-sm text-gray-500">
                            Propietario: {{ $project->owner->name }}
                        </p>
                    </div>

                    @if ($isOwner)
                        <a href="{{ route('projects.edit', $project) }}"
                            class="inline-block rounded-lg bg-violet-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-violet-700">
                            Editar proyecto
                        </a>
                    @endif

                </div>

                <div class="mt-6 border-t pt-6">
                    <h3 class="text-sm font-semibold text-gray-700">
                        Descripción
                    </h3>

                    <p class="mt-2 whitespace-pre-line text-gray-600">
                        {{ $project->description }}
                    </p>
                </div>

                <div class="mt-6 grid gap-4 border-t pt-6 sm:grid-cols-2">

                    <div>
                        <p class="text-sm text-gray-500">
                            Fecha límite
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $project->deadline->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Fecha de creación
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $project->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                </div>
            </section>

            <!-- Participantes -->
            <section class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Participantes
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Personas que colaboran en este proyecto.
                        </p>
                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                        {{ $project->members->count() }}
                    </span>
                </div>

                @if ($project->members->isEmpty())

                    <p class="rounded-lg bg-gray-50 p-5 text-center text-sm text-gray-500">
                        Este proyecto todavía no tiene participantes.
                    </p>

                @else

                    <div class="space-y-4">

                        @foreach ($project->members as $member)

                                    <div class="rounded-lg border border-gray-200 p-4">

                                        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                                            <div>
                                                <h3 class="font-semibold text-gray-900">
                                                    {{ $member->name }}
                                                </h3>

                                                <p class="text-sm text-gray-500">
                                                    {{ $member->email }}
                                                </p>
                                            </div>

                                            <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold
                                                        {{ $member->pivot->role === 'admin'
                            ? 'bg-violet-100 text-violet-800'
                            : 'bg-gray-100 text-gray-700' }}">
                                                {{ $member->pivot->role === 'admin' ? 'Administrador' : 'Participante' }}
                                            </span>

                                        </div>

                                        <p class="mt-3 text-sm text-gray-600">
                                            Horas aportadas:
                                            <strong>{{ $member->pivot->contribution_hours }}</strong>
                                        </p>

                                        @if ($canManageMembers)

                                            <div class="mt-4 border-t pt-4">

                                                <!-- Modificar participante -->
                                                <form action="{{ route('projects.members.update', [$project, $member]) }}" method="POST"
                                                    class="flex flex-col gap-3 sm:flex-row sm:items-end">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-gray-700">
                                                            Rol
                                                        </label>

                                                        <select name="role" required
                                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                            <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>
                                                                Participante
                                                            </option>

                                                            <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>
                                                                Administrador
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-gray-700">
                                                            Horas aportadas
                                                        </label>

                                                        <input type="number" name="contribution_hours" min="0"
                                                            value="{{ $member->pivot->contribution_hours }}" required
                                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                    </div>

                                                    <button type="submit"
                                                        class="rounded-lg bg-violet-600 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                                                        Guardar cambios
                                                    </button>

                                                </form>

                                                <!-- Eliminar participante -->
                                                <form action="{{ route('projects.members.destroy', [$project, $member]) }}" method="POST"
                                                    class="mt-3"
                                                    onsubmit="return confirm('¿Seguro que quieres eliminar a este participante?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">
                                                        Eliminar participante
                                                    </button>
                                                </form>

                                            </div>

                                        @endif

                                    </div>

                        @endforeach

                    </div>

                @endif

            </section>

            <!-- Añadir participantes -->
            @if ($canManageMembers)

                <section class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Añadir participante
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Introduce el correo electrónico de un usuario registrado en Taskora.
                    </p>

                    <form action="{{ route('projects.members.store', $project) }}" method="POST" class="mt-5 space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Correo electrónico
                            </label>

                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                placeholder="usuario@ejemplo.com"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">

                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">
                                    Rol
                                </label>

                                <select name="role" id="role" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                    <option value="member">Participante</option>
                                    <option value="admin">Administrador</option>
                                </select>

                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="contribution_hours" class="block text-sm font-medium text-gray-700">
                                    Horas aportadas
                                </label>

                                <input type="number" name="contribution_hours" id="contribution_hours" min="0" value="0"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">

                                @error('contribution_hours')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <button type="submit"
                            class="rounded-lg bg-violet-600 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                            Añadir participante
                        </button>

                    </form>

                </section>

            @endif

        </div>
    </div>
</x-app-layout>