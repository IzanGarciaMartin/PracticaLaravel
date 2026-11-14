<x-guest-layout>
    <div class="mb-8">
        <p class="mb-2 text-sm font-bold uppercase tracking-wider text-[#713DE0]">
            Bienvenido de nuevo
        </p>

        <h1 class="text-3xl font-extrabold tracking-tight text-[#252238]">
            ¡Qué bueno verte!
        </h1>

        <p class="mt-2 text-sm leading-relaxed text-[#777386]">
            Inicia sesión para continuar organizando tus proyectos.
        </p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="email"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="tu@email.com" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <x-input-label for="password" value="Contraseña" class="text-sm font-semibold text-[#252238]" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-semibold text-[#713DE0] transition hover:text-[#5829BD]">
                        ¿La has olvidado?
                    </a>
                @endif
            </div>

            <x-text-input id="password"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="password" name="password" required autocomplete="current-password"
                placeholder="Introduce tu contraseña" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember"
                class="h-4 w-4 rounded border-[#D6D1E0] text-[#713DE0] shadow-sm focus:ring-[#713DE0]/30">

            <label for="remember_me" class="ms-2 text-sm text-[#777386]">
                Mantener la sesión iniciada
            </label>
        </div>

        <button type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#713DE0] px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-purple-200 transition duration-200 hover:-translate-y-0.5 hover:bg-[#602FCB] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#713DE0] focus:ring-offset-2">
            Iniciar sesión

            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14" />
                <path d="m12 5 7 7-7 7" />
            </svg>
        </button>
    </form>

    <div class="mt-7 border-t border-[#EAE7F0] pt-6 text-center">
        <p class="text-sm text-[#777386]">
            ¿Todavía no tienes una cuenta?

            <a href="{{ route('register') }}" class="ml-1 font-bold text-[#713DE0] transition hover:text-[#5829BD]">
                Regístrate
            </a>
        </p>
    </div>
</x-guest-layout>