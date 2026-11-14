<section class="space-y-6">
    <header class="border-b border-red-100 pb-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M8 6V4h8v2" />
                    <path d="m19 6-1 14H6L5 6" />
                    <path d="M10 11v5M14 11v5" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-red-700">
                    Zona de peligro
                </h2>
                <p class="mt-0.5 text-sm text-[#777386]">
                    Acciones permanentes relacionadas con tu cuenta.
                </p>
            </div>
        </div>
    </header>

    <div>
        <h3 class="text-sm font-bold text-[#252238]">
            Eliminar cuenta
        </h3>

        <p class="mt-1 max-w-2xl text-sm leading-relaxed text-[#777386]">
            Al eliminar tu cuenta, tus proyectos se transferirán a otros participantes siempre que sea posible.
            Esta acción no se puede deshacer.
        </p>
    </div>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-600 transition hover:border-red-300 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
        Eliminar mi cuenta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="mb-6">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                        <path d="M10.3 3.9 1.8 18.2A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                    </svg>
                </div>

                <h2 class="text-xl font-extrabold text-[#252238]">
                    ¿Seguro que quieres eliminar tu cuenta?
                </h2>

                <p class="mt-2 text-sm leading-relaxed text-[#777386]">
                    Antes de eliminarla, transferiremos tus proyectos a los participantes que correspondan.
                    Esta acción es permanente.
                </p>
            </div>

            {{-- Project ownership transfers --}}
            @foreach ($user->ownedProjects as $project)

                @php
                    $eligibleMembers = $project->members
                        ->where('id', '!=', $user->id);

                    $hasAdmin = $eligibleMembers->contains(
                        fn($member) => $member->pivot->role === 'admin'
                    );
                @endphp

                @if (!$hasAdmin)
                    <div class="mt-5 rounded-xl border border-[#EAE7F0] bg-[#F7F6FA] p-4">
                        <x-input-label for="transfer-{{ $project->id }}" value="Nuevo propietario: {{ $project->title }}"
                            class="text-sm font-bold text-[#252238]" />

                        @if ($eligibleMembers->isEmpty())

                            <p class="mt-2 text-sm leading-relaxed text-red-600">
                                Este proyecto no tiene participantes disponibles para asumir la propiedad.
                                Añade un participante o elimina el proyecto antes de eliminar tu cuenta.
                            </p>

                        @else

                            <select name="transfers[{{ $project->id }}]" id="transfer-{{ $project->id }}" required
                                class="mt-2 block w-full rounded-xl border-[#EAE7F0] bg-white px-4 py-3 text-sm text-[#252238] shadow-sm focus:border-[#713DE0] focus:ring-2 focus:ring-[#713DE0]/20">
                                <option value="">Selecciona un nuevo propietario</option>

                                @foreach ($eligibleMembers as $member)
                                    <option value="{{ $member->id }}" @selected(
                                        old('transfers.' . $project->id) == $member->id
                                    )>
                                        {{ $member->name }}
                                        ({{ $member->pivot->contribution_hours }} horas)
                                    </option>
                                @endforeach
                            </select>

                        @endif

                        <x-input-error :messages="$errors->userDeletion->get('transfers.' . $project->id)" class="mt-2" />
                    </div>
                @endif

            @endforeach

            {{-- Password confirmation --}}
            <div class="mt-6">
                <x-input-label for="password" value="Confirma tu contraseña"
                    class="mb-2 text-sm font-semibold text-[#252238]" />

                <x-text-input id="password" name="password" type="password"
                    class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-red-400 focus:bg-white focus:ring-2 focus:ring-red-200"
                    placeholder="Introduce tu contraseña" required />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- Modal buttons --}}
            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="$dispatch('close')"
                    class="rounded-xl border border-[#EAE7F0] bg-white px-5 py-3 text-sm font-bold text-[#777386] transition hover:bg-[#F7F6FA]">
                    Cancelar
                </button>

                <button type="submit"
                    class="rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-red-200 transition hover:bg-red-700">
                    Confirmar eliminación
                </button>
            </div>
        </form>
    </x-modal>
</section>