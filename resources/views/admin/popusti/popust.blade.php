<x-admin-layout>
    @php
        $formatDiscountValue = fn($discountCode) => $discountCode->type === 'percent'
            ? $discountCode->value . '%'
            : formatirajCijenu($discountCode->value) . ' EUR';
        $adminTimezone = \App\Models\DiscountCode::ADMIN_TIMEZONE;
    @endphp

    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <x-naslov-sekcije>{{ __('Detalji koda za popust') }}</x-naslov-sekcije>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/admin/popusti/izmijeni/' . $discountCode->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    {{ __('Izmijeni') }}
                </a>
                <form action="{{ url('/admin/popusti/' . $discountCode->id . '/toggle') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        {{ $discountCode->is_active ? __('Deaktiviraj') : __('Aktiviraj') }}
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white border rounded-lg p-5 shadow-sm">
                <p class="text-sm text-gray-500">{{ __('Kod') }}</p>
                <p class="text-2xl font-bold">{{ $discountCode->code }}</p>
            </div>
            <div class="bg-white border rounded-lg p-5 shadow-sm">
                <p class="text-sm text-gray-500">{{ __('Vrijednost') }}</p>
                <p class="text-2xl font-bold">{{ $formatDiscountValue($discountCode) }}</p>
            </div>
            <div class="bg-white border rounded-lg p-5 shadow-sm">
                <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                <p class="text-2xl font-bold {{ $discountCode->is_active ? 'text-green-700' : 'text-red-700' }}">
                    {{ $discountCode->is_active ? __('Aktivan') : __('Neaktivan') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Korišćenja') }}</p>
                <p class="text-xl font-bold">{{ $discountCode->uses_count }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Redemption zapisi') }}</p>
                <p class="text-xl font-bold">{{ $discountCode->redemptions_count }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Preostalo globalno') }}</p>
                <p class="text-xl font-bold">{{ $discountCode->remainingUses() ?? __('Neograničeno') }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Minimalna porudžbina') }}</p>
                <p class="text-xl font-bold">{{ $discountCode->minimum_order_total ? formatirajCijenu($discountCode->minimum_order_total) . ' EUR' : '-' }}</p>
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-5 mb-8">
            <h3 class="text-lg font-bold mb-4">{{ __('Podešavanja') }}</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-gray-500">{{ __('Važi od') }}</dt>
                    <dd class="font-bold">{{ $discountCode->adminDateTimeDisplay('starts_at') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('Važi do') }}</dt>
                    <dd class="font-bold">{{ $discountCode->adminDateTimeDisplay('expires_at') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('Globalni limit') }}</dt>
                    <dd class="font-bold">{{ $discountCode->max_uses ?? __('Neograničeno') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('Limit po email adresi') }}</dt>
                    <dd class="font-bold">{{ $discountCode->max_uses_per_email ?? __('Neograničeno') }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white border rounded-lg shadow-sm p-5">
            <h3 class="text-lg font-bold mb-4">{{ __('Nedavna korišćenja') }}</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 px-3">{{ __('Porudžbina') }}</th>
                            <th class="py-2 px-3">{{ __('Email') }}</th>
                            <th class="py-2 px-3">{{ __('Korisnik') }}</th>
                            <th class="py-2 px-3">{{ __('Iznos popusta') }}</th>
                            <th class="py-2 px-3">{{ __('Datum') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentRedemptions as $redemption)
                            <tr class="border-b">
                                <td class="py-2 px-3">
                                    @if ($redemption->porudzbina)
                                        <a href="{{ url('/admin/porudzbine/' . $redemption->porudzbina->id) }}" class="text-blue-600 hover:underline">
                                            #{{ $redemption->porudzbina->id }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2 px-3">{{ $redemption->email }}</td>
                                <td class="py-2 px-3">{{ $redemption->user?->email ?? '-' }}</td>
                                <td class="py-2 px-3">{{ formatirajCijenu($redemption->discount_amount) }} EUR</td>
                                <td class="py-2 px-3">{{ $redemption->created_at->timezone($adminTimezone)->format('d.m.Y. H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center font-semibold">{{ __('Nema korišćenja za prikaz.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
