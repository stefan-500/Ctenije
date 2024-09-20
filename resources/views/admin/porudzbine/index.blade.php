<x-admin-layout>
    <div x-data="{ showDeleteModal: false, itemToDelete: null }" class="relative">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Porudžbine') }}</x-naslov-sekcije>

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

        <x-tabela :showActions="false">
            <x-slot name="thead">
                <th class="py-2 px-4">{{ __('ID') }}</th>
                <th class="py-2 px-4">{{ __('Datum') }}</th>
                <th class="py-2 px-4">{{ __('Adresa isporuke') }}</th>
                <th class="py-2 px-4">{{ __('Ukupno') }}<span class="uppercase text-xs font-bold"> (eur)</span></th>
                <th class="py-2 px-4">{{ __('Status') }}</th>
            </x-slot>

            @foreach ($porudzbine as $porudzbina)
                <tr class="border-b hover:bg-gray-100 cursor-pointer text-center"
                    onclick="window.location='{{ url('/admin/porudzbine/' . $porudzbina->id) }}'">
                    <td class="py-2 px-4">{{ $porudzbina->id }}</td>
                    <td class="py-2 px-4">{{ __($porudzbina->created_at->format('d.m.Y H:m')) }}</td>
                    <td class="py-2 px-4">{{ __($porudzbina->adresa_isporuke) }}</td>
                    <td class="py-2 px-4">{{ __($porudzbina->ukupno) }}</td>
                    <td class="py-2 px-4">{{ __($porudzbina->status) }}</td>
                </tr>
            @endforeach
        </x-tabela>

        <x-delete-confirmation-modal url-prefix="/admin/porudzbine" entitet="{{ __('porudžbina') }}" />
    </div>
</x-admin-layout>
