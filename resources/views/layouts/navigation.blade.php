<nav x-data="{ open: false, userMenu: false }"
    class="sticky top-0 z-50 border-b border-[#EAE7F0] bg-white/95 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">

            {{-- Logo --}}
            <div class="flex shrink-0 items-center">
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#713DE0] text-white shadow-lg shadow-purple-200 transition duration-200 group-hover:-translate-y-0.5 group-hover:shadow-xl">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12.5L9.5 17L19 7.5" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M4 4H20V20H4V4Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"
                                opacity="0.35" />
                        </svg>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold tracking-tight text-[#252238]">
                            Taskora<span class="text-[#713DE0]">.</span>
                        </span>
                        <span class="-mt-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-[#9994A8]">
                            Workspace
                        </span>
                    </div>
                </a>
            </div>

            {{-- Navegación principal --}}
            <div class="hidden items-center gap-2 sm:flex">
                <a href="{{ route('dashboard') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold transition duration-200
                   {{ request()->routeIs('dashboard') ? 'bg-[#F1EBFC] text-[#713DE0]' : 'text-[#777386] hover:bg-[#F7F6FA] hover:text-[#252238]' }}">
                    Dashboard
                </a>

                <a href="{{ route('projects.index') }}"
                    class="rounded-xl px-4 py-2.5 text-sm font-semibold transition duration-200
                   {{ request()->routeIs('projects.*') ? 'bg-[#F1EBFC] text-[#713DE0]' : 'text-[#777386] hover:bg-[#F7F6FA] hover:text-[#252238]' }}">
                    Projects
                </a>
            </div>

            {{-- Menú de usuario --}}
            <div class="hidden items-center sm:flex">
                <div class="relative" @click.outside="userMenu = false">
                    <button @click="userMenu = !userMenu"
                        class="flex items-center gap-3 rounded-xl border border-transparent px-3 py-2 transition hover:border-[#EAE7F0] hover:bg-[#F7F6FA]">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-[#D6B5FE] text-sm font-bold text-[#5424A8]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="text-left">
                            <p class="text-sm font-bold text-[#252238]">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#9994A8]">Mi cuenta</p>
                        </div>

                        <svg class="h-4 w-4 text-[#9994A8] transition duration-200" :class="{ 'rotate-180': userMenu }"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-cloak x-show="userMenu" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute right-0 mt-3 w-56 overflow-hidden rounded-2xl border border-[#EAE7F0] bg-white p-2 shadow-xl shadow-purple-100/50">

                        <div class="border-b border-[#F0EDF5] px-3 py-3">
                            <p class="truncate text-sm font-bold text-[#252238]">{{ Auth::user()->name }}</p>
                            <p class="truncate text-xs text-[#9994A8]">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                            class="mt-2 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-[#777386] transition hover:bg-[#F1EBFC] hover:text-[#713DE0]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M5 21V19C5 15.686 7.686 13 11 13H13C16.314 13 19 15.686 19 19V21" />
                            </svg>
                            Mi perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-red-500 transition hover:bg-red-50">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M10 17L15 12L10 7" />
                                    <path d="M15 12H3" />
                                    <path d="M12 3H19C20.105 3 21 3.895 21 5V19C21 20.105 20.105 21 19 21H12" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Botón menú móvil --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="rounded-xl p-2.5 text-[#777386] transition hover:bg-[#F1EBFC] hover:text-[#713DE0]"
                    aria-label="Abrir menú">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6H20M4 12H20M4 18H20" />
                    </svg>
                    <svg x-cloak x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6L18 18" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Navegación móvil --}}
    <div x-cloak x-show="open" x-transition class="border-t border-[#EAE7F0] bg-white px-4 pb-5 pt-3 sm:hidden">

        <div class="space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block rounded-xl px-4 py-3 text-sm font-semibold transition
               {{ request()->routeIs('dashboard') ? 'bg-[#F1EBFC] text-[#713DE0]' : 'text-[#777386] hover:bg-[#F7F6FA]' }}">
                Dashboard
            </a>

            <a href="{{ route('projects.index') }}"
                class="block rounded-xl px-4 py-3 text-sm font-semibold transition
               {{ request()->routeIs('projects.*') ? 'bg-[#F1EBFC] text-[#713DE0]' : 'text-[#777386] hover:bg-[#F7F6FA]' }}">
                Projects
            </a>

            <a href="{{ route('profile.edit') }}"
                class="block rounded-xl px-4 py-3 text-sm font-semibold text-[#777386] transition hover:bg-[#F7F6FA]">
                Mi perfil
            </a>
        </div>

        <div class="mt-4 border-t border-[#EAE7F0] pt-4">
            <p class="px-4 text-sm font-bold text-[#252238]">{{ Auth::user()->name }}</p>
            <p class="px-4 text-xs text-[#9994A8]">{{ Auth::user()->email }}</p>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-500 transition hover:bg-red-50">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>