<x-app-layout>
    <div class="container mx-auto px-4">

        <h2 class="text-3xl font-bold my-12 text-center">{{ $vrstaArtikla->naziv ?? __('Knjige') }}</h2>

        <!-- Kartice -->
        <div class="flex flex-wrap -mx-3 text-naslov">
            @foreach ($knjige as $knjiga)
                <a class="block" href="/knjige/knjiga/{{ $knjiga->artikal_id }}">
                    <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/4 mb-4 px-3">
                        <!-- Ensure full height card and distribute space using Flexbox -->
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-full">
                            <!-- Image -->
                            @if ($knjiga->artikal->artikalSlike->isNotEmpty())
                                <img src="{{ asset('img/' . $knjiga->artikal->artikalSlike->first()->naziv_fajla) }}"
                                    class="w-full h-72 object-contain" alt="{{ $knjiga->artikal->naziv }}">
                            @else
                                <img src="{{ asset('img/fajl-nije-pronadjen.png') }}" class="w-full h-72 object-contain"
                                    alt="{{ __('Fajl nije pronađen') }}">
                            @endif

                            <!-- Book Info Section with Flex-grow -->
                            <div class="p-4 flex-grow">
                                <h5 class="text-lg font-bold text-center">{{ $knjiga->artikal->naziv }}</h5>
                                <p class="text-tekst mt-2">
                                    {{ $knjiga->artikal->kratkiOpis }}
                                </p>
                            </div>

                            <!-- Price and Button Section always at the bottom -->
                            <div class="mt-auto px-4 pb-4">
                                <div
                                    class="flex justify-between items-center space-y-4 sm:space-y-0 flex-col sm:flex-row">
                                    <div class="flex items-center text-lg mb-4 sm:mb-0">
                                        <i class="fa-solid fa-tag text-sm text-tekst mr-1"></i>
                                        <span class="text-xl font-bold">{{ $knjiga->artikal->formatiranaCijena }}</span>
                                        <span class="ml-1 text-sm font-semibold text-tekst">EUR</span>
                                    </div>
                                    <!-- Add To Cart -->
                                    <div class="w-full sm:w-auto">
                                        <a href="" data-artikal-id="{{ $knjiga->artikal->id }}"
                                            class="bg-green-500 text-white text-xl hover:bg-green-600 transition w-full sm:w-auto px-11 py-2 rounded-lg flex items-center justify-center add-to-cart">
                                            <i class="fa-solid fa-cart-plus text-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Linkovi za paginaciju -->
        <div class="m-12 px-12">
            {{ $knjige->links() }}
        </div>
    </div>
</x-app-layout>
