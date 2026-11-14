<x-guest-layout>
    <div class="mb-8">
        <p class="mb-2 text-sm font-bold uppercase tracking-wider text-[#713DE0]">
            Empieza hoy
        </p>

        <h1 class="text-3xl font-extrabold tracking-tight text-[#252238]">
            Crea tu cuenta
        </h1>

        <p class="mt-2 text-sm leading-relaxed text-[#777386]">
            Organiza tus ideas, gestiona tus proyectos y alcanza tus objetivos.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nombre completo" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="name"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="Tu nombre" />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="email"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="tu@email.com" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Contraseña" class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="password"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="password" name="password" required autocomplete="new-password"
                placeholder="Crea una contraseña" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmar contraseña"
                class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="password_confirmation"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Repite tu contraseña" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#713DE0] px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-purple-200 transition duration-200 hover:-translate-y-0.5 hover:bg-[#602FCB] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#713DE0] focus:ring-offset-2">
            Crear cuenta

            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
        </button>
    </form>

    <div class="mt-7 border-t border-[#EAE7F0] pt-6 text-center">
        <p class="text-sm text-[#777386]">
            ¿Ya tienes una cuenta?

            <a href="{{ route('login') }}" class="ml-1 font-bold text-[#713DE0] transition hover:text-[#5829BD]">
                Inicia sesión
            </a>
        </p>
    </div>
</x-guest-layout>