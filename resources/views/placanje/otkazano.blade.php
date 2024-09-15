<x-app-layout>
    <div class="container mx-auto my-[9rem] text-center">
        <x-naslov-sekcije class="mb-4">{{ __('Plaćanje otkazano') }}</x-naslov-sekcije>
        <p class="text-tekst text-lg font-bold">
            {{ __('Plaćanje je otkazano ili nije uspijelo. Možete pokušati ponovo.') }}
        </p>
        <a href="{{ url('/cart') }}"
            class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
            {{ __('Povratak u korpu') }}
        </a>
    </div>
</x-app-layout>
