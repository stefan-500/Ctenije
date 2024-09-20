<x-admin-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-naslov-sekcije class="text-center mb-10">{{ __('Detalji Porudžbine') }}</x-naslov-sekcije>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-10 text-lg font-medium text-tekst">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-bold">{{ __('Informacije o porudžbini') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Detalji i informacije o porudžbini.') }}</p>
            </div>
            <div class="border-t border-gray-200 text-base">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-base">{{ __('Broj porudžbine') }}</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">{{ $porudzbina->id }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-base">{{ __('Datum') }}</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">
                            {{ __($porudzbina->created_at->format('d.m.Y - H:i')) }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-base">{{ __('Status') }}</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">{{ __($porudzbina->status) }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-base">{{ __('Ukupan iznos') }}</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">{{ __($porudzbina->ukupno) }} <span
                                class="uppercase text-xs font-bold">eur</span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-base">{{ __('Adresa isporuke') }}</dt>
                        <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">
                            {{ __($porudzbina->adresa_isporuke) }}</dd>
                    </div>
                    @if ($porudzbina->user)
                        {{-- Registrovani korisnik --}}
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-base">{{ __('Korisnik') }}</dt>
                            <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">
                                {{ __($porudzbina->user->name) }} ({{ __($porudzbina->user->email) }})
                            </dd>
                        </div>
                    @elseif($porudzbina->guestDeliveryData)
                        {{-- Neregistrovani korisnik --}}
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-base">{{ __('Gost') }}</dt>
                            <dd class="mt-1 sm:mt-0 sm:col-span-2 font-semibold">
                                {{ __($porudzbina->guestDeliveryData->ime_prezime) }}
                                ({{ __($porudzbina->guestDeliveryData->email) }})
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="flex flex-col text-tekst">
            <div class="mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Stavke porudžbine') }}</h3>
            </div>
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm uppercase tracking-wider">
                                        {{ __('Naziv artikla') }}
                                    </th>
                                    <th class="px-6 py-3 text-center text-sm uppercase tracking-wider">
                                        {{ __('Količina') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-sm uppercase tracking-wider">
                                        {{ __('Cijena po komadu') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-sm uppercase tracking-wider">
                                        {{ __('Ukupno') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($porudzbina->stavkePorudzbine as $stavka)
                                    <tr class="font-semibold">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>{{ __($stavka->artikal->naziv) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div>{{ $stavka->kolicina }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div>
                                                {{ $stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena }}
                                                <span class="uppercase text-xs font-bold">eur</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div>
                                                {{ $stavka->ukupna_cijena }} <span
                                                    class="uppercase text-xs font-bold">eur</span></div>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- Order Total --}}
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right font-bold text-xl">
                                        {{ __('Ukupno:') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-xl">
                                        {{ __($porudzbina->ukupno) }} <span
                                            class="uppercase text-xs font-bold">eur</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
