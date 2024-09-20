<x-admin-layout>
    <div x-data="{ showDeleteModal: false, itemToDelete: null }" class="relative">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Korisnici') }}</x-naslov-sekcije>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <x-tabela>
            <x-slot name="thead">
                <th class="py-2 px-4">{{ __('ID') }}</th>
                <th class="py-2 px-4">{{ __('Ime') }}</th>
                <th class="py-2 px-4">{{ __('Prezime') }}</th>
                <th class="py-2 px-4">{{ __('Email') }}</th>
                <th class="py-2 px-4">{{ __('Telefon') }}</th>
                <th class="py-2 px-4">{{ __('Adresa') }}</th>
                <th class="py-2 px-4">{{ __('Registrovan') }}</th>
            </x-slot>

            @foreach ($korisnici as $korisnik)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2 px-4 text-center">{{ $korisnik->id }}</td>
                    <td class="py-2 px-4">{{ __($korisnik->ime) }}</td>
                    <td class="py-2 px-4">{{ __($korisnik->prezime) }}</td>
                    <td class="py-2 px-4">{{ __($korisnik->email) }}</td>
                    <td class="py-2 px-4">{{ __($korisnik->tel) }}</td>
                    <td class="py-2 px-4">{{ __($korisnik->adresa) }}</td>
                    <td class="py-2 px-4">{{ $korisnik->created_at->format('d.m.Y') }}</td>
                    <td class="py-2 px-4 text-center">
                        <button
                            @click="itemToDelete = { id: {{ $korisnik->id }}, name: '{{ addslashes($korisnik->ime . ' ' . $korisnik->prezime) }}' }; showDeleteModal = true"
                            class="text-red-500 hover:text-red-700 focus:outline-none">
                            <i class="fa-solid fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </x-tabela>

        <x-delete-confirmation-modal url-prefix="/admin/korisnici" />
    </div>
</x-admin-layout>
