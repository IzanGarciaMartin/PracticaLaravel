<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Editar proyecto
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Modificar información
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Actualiza los datos de tu proyecto.
                    </p>
                </div>

                <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Título -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Nombre del proyecto
                        </label>

                        <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                            maxlength="255" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Descripción
                        </label>

                        <textarea name="description" id="description" rows="5" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha límite -->
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700">
                            Fecha límite
                        </label>

                        <input type="date" name="deadline" id="deadline"
                            value="{{ old('deadline', $project->deadline->format('Y-m-d')) }}"
                            min="{{ now()->toDateString() }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">

                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Estado
                        </label>

                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500">
                            <option value="pending" {{ old('status', $project->status) === 'pending' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="in_progress" {{ old('status', $project->status) === 'in_progress' ? 'selected' : '' }}>
                                En progreso
                            </option>

                            <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>
                                Completado
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end gap-3 border-t pt-6">

                        <a href="{{ route('projects.show', $project) }}"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>

                        <button type="submit"
                            class="rounded-lg bg-violet-600 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-700">
                            Guardar cambios
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>