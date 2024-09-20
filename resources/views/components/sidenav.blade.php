@php
    $isAdmin = Auth::user()->ovlascenje === 'Administrator';
@endphp
<div x-data="{ open: false, activeMenu: null }"
    class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform 
    lg:relative lg:translate-x-0 transition duration-200 ease-in-out z-50"
    :class="{ '-translate-x-full': !open }">

    <!-- Logo -->
    <a href="{{ url('/admin') }}" class="text-white flex items-center space-x-2 px-4">
        <span class="text-2xl font-extrabold">{{ __('Admin') }}</span>
    </a>

    <nav>
        <ul>
            <!-- Dashboard -->
            <li>
                <a href="{{ url('/admin') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700
                   {{ request()->is('admin') ? 'bg-gray-700' : '' }}">
                    <i class="fa-solid fa-tachometer-alt mr-3"></i>
                    {{ __('Dashboard') }}
                </a>
            </li>

            <!-- Korisnici -->
            <li>
                <button @click="activeMenu === 'korisnici' ? activeMenu = null : activeMenu = 'korisnici'"
                    class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fa-solid fa-users mr-3"></i>
                        <span>{{ __('Korisnici') }}</span>
                    </div>
                    <i :class="activeMenu === 'korisnici' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
                </button>
                <ul x-show="activeMenu === 'korisnici'" x-transition class="pl-6">
                    <li>
                        <a href="{{ url('/admin/korisnici/index') }}"
                            class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('admin/korisnici/index') ? 'bg-gray-700' : '' }}">
                            {{ __('Svi Korisnici') }}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Menadzeri -->
            <li>
                <button @click="activeMenu === 'menadzeri' ? activeMenu = null : activeMenu = 'menadzeri'"
                    class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fa-solid fa-users mr-3"></i>
                        <span>{{ __('Menadžeri') }}</span>
                    </div>
                    <i :class="activeMenu === 'menadzeri' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
                </button>
                <ul x-show="activeMenu === 'menadzeri'" x-transition class="pl-6">
                    <li>
                        <a href="{{ url('/admin/menadzeri/index') }}"
                            class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('admin/menadzeri/index') ? 'bg-gray-700' : '' }}">
                            {{ __('Svi Menadžeri') }}
                        </a>
                    </li>
                    @if ($isAdmin)
                        <li>
                            <a href="{{ url('/admin/menadzeri/dodaj') }}"
                                class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('/admin/menadzeri/dodaj') ? 'bg-gray-700' : '' }}">
                                {{ __('Dodaj Menadžera') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- Artikli -->
            <li>
                <button @click="activeMenu === 'artikli' ? activeMenu = null : activeMenu = 'artikli'"
                    class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fa-solid fa-box-open mr-3"></i>
                        <span>{{ __('Artikli') }}</span>
                    </div>
                    <i :class="activeMenu === 'artikli' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
                </button>
                <ul x-show="activeMenu === 'artikli'" x-transition class="pl-6">
                    <li>
                        <a href="{{ url('/admin/artikli/index') }}"
                            class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('/admin/artikli/index') ? 'bg-gray-700' : '' }}">
                            {{ __('Svi Artikli') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/artikli/dodaj') }}"
                            class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('/admin/artikli/dodaj') ? 'bg-gray-700' : '' }}">
                            {{ __('Dodaj Artikal') }}
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Porudzbine -->
            <li>
                <button @click="activeMenu === 'porudzbine' ? activeMenu = null : activeMenu = 'porudzbine'"
                    class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 focus:outline-none">
                    <div class="flex items-center">
                        <i class="fa-solid fa-shopping-cart mr-3"></i>
                        <span>{{ __('Porudžbine') }}</span>
                    </div>
                    <i :class="activeMenu === 'porudzbine' ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
                </button>
                <ul x-show="activeMenu === 'porudzbine'" x-transition class="pl-6">
                    <li>
                        <a href="{{ url('/admin/porudzbine/index') }}"
                            class="block py-2 px-4 rounded transition duration-200 hover:bg-gray-700
                           {{ request()->is('admin/porudzbine/index') ? 'bg-gray-700' : '' }}">
                            {{ __('Sve Porudžbine') }}
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</div>

{{-- Toggle button --}}
<div class="fixed top-4 left-4 lg:hidden z-50">
    <button @click="open = !open" class="text-white focus:outline-none">
        <i class="fa-solid fa-bars fa-2x"></i>
    </button>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidenav', () => ({
                open: false,
                activeMenu: null,
            }))
        })
    </script>
@endpush
