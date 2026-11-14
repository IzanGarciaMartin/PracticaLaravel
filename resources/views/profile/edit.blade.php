<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-semibold text-[#713DE0]">CONFIGURACIÓN</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#252238]">
                Mi perfil
            </h1>
            <p class="mt-1 text-sm text-[#777386]">
                Gestiona tu información personal y la seguridad de tu cuenta.
            </p>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-5xl space-y-7 px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-4 rounded-2xl border border-[#EAE7F0] bg-white p-5 shadow-sm">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#713DE0] text-xl font-extrabold text-white shadow-lg shadow-purple-200">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <h2 class="truncate text-lg font-bold text-[#252238]">
                        {{ $user->name }}
                    </h2>
                    <p class="truncate text-sm text-[#777386]">
                        {{ $user->email }}
                    </p>
                </div>

                <div
                    class="ml-auto hidden rounded-full bg-purple-50 px-3 py-1.5 text-xs font-bold text-[#713DE0] sm:block">
                    Mi cuenta
                </div>
            </div>

            <div class="rounded-2xl border border-[#EAE7F0] bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl border border-[#EAE7F0] bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>