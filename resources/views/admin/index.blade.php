<x-admin-layout>
    <x-naslov-sekcije class="text-center mb-10">Dashboard</x-naslov-sekcije>

    <div class="text-2xl text-tekst font-semibold mb-6">{{ __('Dobrodošli ') . Auth::user()->ime . '!' }}</div>

    <!-- Add your dashboard content here -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Example Cards -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('Ukupno korisnika') }}</h3>
                    <p class="text-2xl font-semibold">{{ $ukupnoKorisnika }}</p>
                </div>
                <i class="fa-solid fa-users text-4xl text-blue-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('Ukupno menadžera') }}</h3>
                    <p class="text-2xl font-semibold">{{ $ukupnoMenadzera }}</p>
                </div>
                <i class="fa-solid fa-users text-4xl text-blue-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('Ukupno porudžbina') }}</h3>
                    <p class="text-2xl font-semibold">{{ $ukupnoPorudzbina }}</p>
                </div>
                <i class="fa-solid fa-shopping-cart text-4xl text-green-500"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ __('Ukupno artikala') }}</h3>
                    <p class="text-2xl font-semibold">{{ $ukupnoArtikala }}</p>
                </div>
                <i class="fa-solid fa-box-open text-4xl text-yellow-500"></i>
            </div>
        </div>
    </div>

</x-admin-layout>
