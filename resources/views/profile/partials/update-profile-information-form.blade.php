<section class="space-y-6">
    <header class="border-b border-[#EAE7F0] pb-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M5 21a7 7 0 0 1 14 0" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#252238]">
                    Información personal
                </h2>
                <p class="mt-0.5 text-sm text-[#777386]">
                    Actualiza tu nombre y dirección de correo electrónico.
                </p>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nombre completo" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="name" name="name" type="text"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="email" name="email" type="email"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                :value="old('email', $user->email)" required autocomplete="username" />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm font-semibold text-amber-800">
                        Tu dirección de correo todavía no está verificada.
                    </p>

                    <button form="send-verification"
                        class="mt-2 text-sm font-bold text-amber-700 underline decoration-amber-300 underline-offset-4 hover:text-amber-900">
                        Reenviar correo de verificación
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-700">
                            Se ha enviado un nuevo enlace de verificación.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <button type="submit"
                class="rounded-xl bg-[#713DE0] px-5 py-3 text-sm font-bold text-white shadow-md shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-[#602FCB]">
                Guardar cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-semibold text-green-600">
                    ¡Cambios guardados!
                </p>
            @endif
        </div>
    </form>
</section>