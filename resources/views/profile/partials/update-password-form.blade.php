<section class="space-y-6">
    <header class="border-b border-[#EAE7F0] pb-5">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-[#713DE0]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    <path d="M12 15v3" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#252238]">
                    Seguridad de la cuenta
                </h2>
                <p class="mt-0.5 text-sm text-[#777386]">
                    Cambia tu contraseña para mantener tu cuenta protegida.
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Contraseña actual"
                class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                autocomplete="current-password" />

            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Nueva contraseña"
                class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="update_password_password" name="password" type="password"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                autocomplete="new-password" />

            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Confirmar nueva contraseña"
                class="mb-2 text-sm font-semibold text-[#252238]" />

            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="block w-full rounded-xl border-[#EAE7F0] bg-[#F7F6FA] px-4 py-3 text-sm text-[#252238] shadow-none transition focus:border-[#713DE0] focus:bg-white focus:ring-2 focus:ring-[#713DE0]/20"
                autocomplete="new-password" />

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center gap-4 pt-2">
            <button type="submit"
                class="rounded-xl bg-[#713DE0] px-5 py-3 text-sm font-bold text-white shadow-md shadow-purple-200 transition hover:-translate-y-0.5 hover:bg-[#602FCB]">
                Actualizar contraseña
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-semibold text-green-600">
                    ¡Contraseña actualizada!
                </p>
            @endif
        </div>
    </form>
</section>