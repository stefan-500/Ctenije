<x-admin-layout>
    <div x-data="{ showDeleteModal: false, itemToDelete: null }" class="relative">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Artikli') }}</x-naslov-sekcije>

        <div class="flex justify-end items-center mb-6">
            <a href="{{ url('/admin/artikli/dodaj') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>
                {{ __('Dodaj Artikal') }}
            </a>
        </div>

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
                <th class="py-2 px-4">{{ __('Naziv') }}</th>
                <th class="py-2 px-4">{{ __('Cijena') }}<span class="uppercase text-xs font-bold"> (eur)</span></th>
                <th class="py-2 px-4">{{ __('Akcijska Cijena') }}</th>
                <th class="py-2 px-4">{{ __('Dostupna Količina') }}</th>
            </x-slot>

            @foreach ($artikli as $artikal)
                <tr class="border-b hover:bg-gray-100 cursor-pointer text-center"
                    onclick="window.location='{{ url('/knjige/knjiga/' . $artikal->id) }}'">
                    <td class="py-2 px-4">{{ $artikal->id }}</td>
                    <td class="py-2 px-4">{{ __($artikal->naziv) }}</td>
                    <td class="py-2 px-4">{{ __($artikal->cijena) }}</td>
                    <td class="py-2 px-4">{{ __($artikal->akcijska_cijena ?? '-') }}</td>
                    <td class="py-2 px-4">{{ __($artikal->dostupna_kolicina) }}</td>
                    <td class="py-2 px-4 text-center">
                        {{-- Edit Icon --}}
                        <a href="{{ url('/admin/artikli/izmijeni/' . $artikal->id) }}"
                            class="text-blue-500 hover:text-blue-700 mr-2">
                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                        </a>
                        {{-- Delete Icon --}}
                        <button
                            @click.stop="itemToDelete = { id: {{ $artikal->id }}, name: '{{ addslashes($artikal->naziv) }}' }; showDeleteModal = true"
                            class="text-red-500 hover:text-red-700 focus:outline-none">
                            <i class="fa-solid fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </x-tabela>

        <x-delete-confirmation-modal url-prefix="/admin/artikli" entitet="{{ __('artikal') }}" />
    </div>
</x-admin-layout>
