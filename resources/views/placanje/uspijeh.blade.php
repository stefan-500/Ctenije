<x-app-layout>
    <div class="container mx-auto my-[9rem] text-center">
        <x-naslov-sekcije class="mb-4">{{ __('Plaćanje uspiješno!') }}</x-naslov-sekcije>
        <p class="text-tekst text-lg font-bold">{{ __('Vaša porudžbina je uspiješno plaćena. Hvala na kupovini!') }}</p>
        <a href="{{ url('/') }}"
            class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            {{ __('Nazad na početnu') }}
        </a>
    </div>
</x-app-layout>
