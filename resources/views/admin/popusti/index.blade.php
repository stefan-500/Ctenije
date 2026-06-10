<x-admin-layout>
    <div class="relative">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Kodovi za popust') }}</x-naslov-sekcije>

        <div class="flex justify-end items-center mb-6">
            <a href="{{ url('/admin/popusti/dodaj') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>
                {{ __('Dodaj kod') }}
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $formatDiscountValue = function ($discountCode) {
                return $discountCode->type === 'percent'
                    ? $discountCode->value . '%'
                    : formatirajCijenu($discountCode->value) . ' EUR';
            };
        @endphp

        <div class="hidden lg:block">
            <x-tabela>
                <x-slot name="thead">
                    <th class="py-2 px-4">{{ __('Kod') }}</th>
                    <th class="py-2 px-4">{{ __('Tip') }}</th>
                    <th class="py-2 px-4">{{ __('Vrijednost') }}</th>
                    <th class="py-2 px-4">{{ __('Status') }}</th>
                    <th class="py-2 px-4">{{ __('Važi od') }}</th>
                    <th class="py-2 px-4">{{ __('Važi do') }}</th>
                    <th class="py-2 px-4">{{ __('Korišćenja') }}</th>
                    <th class="py-2 px-4">{{ __('Preostalo') }}</th>
                    <th class="py-2 px-4">{{ __('Redemptions') }}</th>
                </x-slot>

                @foreach ($discountCodes as $discountCode)
                    <tr class="border-b hover:bg-gray-100 text-center">
                        <td class="py-2 px-4 font-bold">{{ $discountCode->code }}</td>
                        <td class="py-2 px-4">{{ $discountCode->type === 'percent' ? __('Procenat') : __('Fiksni iznos') }}</td>
                        <td class="py-2 px-4">{{ $formatDiscountValue($discountCode) }}</td>
                        <td class="py-2 px-4">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $discountCode->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $discountCode->is_active ? __('Aktivan') : __('Neaktivan') }}
                            </span>
                        </td>
                        <td class="py-2 px-4">{{ $discountCode->adminDateTimeDisplay('starts_at') ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $discountCode->adminDateTimeDisplay('expires_at') ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $discountCode->uses_count }}</td>
                        <td class="py-2 px-4">{{ $discountCode->remainingUses() ?? __('Neograničeno') }}</td>
                        <td class="py-2 px-4">{{ $discountCode->redemptions_count }}</td>
                        <td class="py-2 px-4">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ url('/admin/popusti/' . $discountCode->id) }}" class="text-gray-600 hover:text-gray-800" title="{{ __('Detalji') }}">
                                    <i class="fa-solid fa-eye fa-lg"></i>
                                </a>
                                <a href="{{ url('/admin/popusti/izmijeni/' . $discountCode->id) }}" class="text-blue-500 hover:text-blue-700" title="{{ __('Izmijeni') }}">
                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                </a>
                                <form action="{{ url('/admin/popusti/' . $discountCode->id . '/toggle') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-700" title="{{ $discountCode->is_active ? __('Deaktiviraj') : __('Aktiviraj') }}">
                                        <i class="fa-solid {{ $discountCode->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-tabela>
        </div>

        <div class="lg:hidden space-y-4">
            @forelse ($discountCodes as $discountCode)
                <div class="bg-white border rounded-lg shadow-sm p-4">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-naslov">{{ $discountCode->code }}</h3>
                            <p class="text-sm text-gray-600">{{ $discountCode->type === 'percent' ? __('Procenat') : __('Fiksni iznos') }} - {{ $formatDiscountValue($discountCode) }}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $discountCode->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $discountCode->is_active ? __('Aktivan') : __('Neaktivan') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                        <div>
                            <p class="text-gray-500">{{ __('Korišćenja') }}</p>
                            <p class="font-bold">{{ $discountCode->uses_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">{{ __('Preostalo') }}</p>
                            <p class="font-bold">{{ $discountCode->remainingUses() ?? __('Neograničeno') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">{{ __('Važi od') }}</p>
                            <p class="font-bold">{{ $discountCode->adminDateTimeDisplay('starts_at') ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">{{ __('Važi do') }}</p>
                            <p class="font-bold">{{ $discountCode->adminDateTimeDisplay('expires_at') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mt-4">
                        <a href="{{ url('/admin/popusti/' . $discountCode->id) }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded">{{ __('Detalji') }}</a>
                        <a href="{{ url('/admin/popusti/izmijeni/' . $discountCode->id) }}" class="px-3 py-2 bg-blue-500 text-white rounded">{{ __('Izmijeni') }}</a>
                        <form action="{{ url('/admin/popusti/' . $discountCode->id . '/toggle') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 bg-yellow-500 text-white rounded">
                                {{ $discountCode->is_active ? __('Deaktiviraj') : __('Aktiviraj') }}
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white border rounded-lg p-6 text-center font-semibold">
                    {{ __('Nema kodova za prikaz.') }}
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
