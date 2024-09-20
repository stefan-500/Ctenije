@php
    $isAdmin = Auth::user()->ovlascenje === 'Administrator';
@endphp
<x-admin-layout>
    <div x-data="{ showDeleteModal: false, itemToDelete: null }" class="relative">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Menadžeri') }}</x-naslov-sekcije>

        <!-- Uslovni prikaz dugmeta "Dodaj Menadžera"  -->
        @if ($isAdmin)
            <div class="flex justify-end items-center mb-6">
                <a href="{{ url('/admin/menadzeri/dodaj') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fa-solid fa-user-plus mr-2"></i>
                    {{ __('Dodaj Menadžera') }}
                </a>
            </div>
        @endif

        <!-- Session Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <!-- Session Error Message -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <x-tabela showActions="{{ $isAdmin }}">
            <x-slot name="thead">
                <th class="py-2 px-4">{{ __('ID') }}</th>
                <th class="py-2 px-4">{{ __('Ime') }}</th>
                <th class="py-2 px-4">{{ __('Prezime') }}</th>
                <th class="py-2 px-4">{{ __('Email') }}</th>
                <th class="py-2 px-4">{{ __('Telefon') }}</th>
                <th class="py-2 px-4">{{ __('Adresa') }}</th>
                <th class="py-2 px-4">{{ __('Registrovan') }}</th>
            </x-slot>

            @foreach ($menadzeri as $menadzer)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2 px-4 text-center">{{ $menadzer->id }}</td>
                    <td class="py-2 px-4">{{ __($menadzer->ime) }}</td>
                    <td class="py-2 px-4">{{ __($menadzer->prezime) }}</td>
                    <td class="py-2 px-4">{{ __($menadzer->email) }}</td>
                    <td class="py-2 px-4">{{ __($menadzer->tel) }}</td>
                    <td class="py-2 px-4">{{ __($menadzer->adresa) }}</td>
                    <td class="py-2 px-4">{{ $menadzer->created_at->format('d.m.Y') }}</td>
                    @if ($isAdmin)
                        <td class="py-2 px-4 text-center">
                            <button
                                @click="itemToDelete = { id: {{ $menadzer->id }}, name: '{{ addslashes($menadzer->ime . ' ' . $menadzer->prezime) }}' }; showDeleteModal = true"
                                class="text-red-500 hover:text-red-700 focus:outline-none">
                                <i class="fa-solid fa-trash fa-lg"></i>
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </x-tabela>

        <!-- Uslovni prikaz delete confirmation modal-a -->
        @if ($isAdmin)
            <x-delete-confirmation-modal url-prefix="/admin/menadzeri" entitet="{{ __('menadžera') }}" />
        @endif
    </div>
</x-admin-layout>
