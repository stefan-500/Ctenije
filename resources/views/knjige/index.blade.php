<x-app-layout>
    <div class="container mx-auto px-4">

        <h2 class="text-3xl font-bold my-12 text-center">{{ $vrstaArtikla->naziv ?? __('Knjige') }}</h2>

        <!-- Kartice -->
        {{-- <div class="flex flex-wrap -mx-3 text-naslov">
            @foreach ($knjige as $knjiga)
                <a class="block" href="/knjige/knjiga/{{ $knjiga->artikal_id }}">
                    <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/4 mb-4 px-3">
                        <!-- Ensure full height card and distribute space using Flexbox -->
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-full">
                            <!-- Image -->
                            @if ($knjiga->artikal->artikalSlike->isNotEmpty())
                                <img src="{{ asset('storage/img/' . $knjiga->artikal->artikalSlike->first()->naziv_fajla) }}"
                                    class="w-full h-72 object-contain" alt="{{ $knjiga->artikal->naziv }}">
                            @else
                                <img src="{{ asset('storage/img/fajl-nije-pronadjen.png') }}"
                                    class="w-full h-72 object-contain" alt="{{ __('Fajl nije pronađen') }}">
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
        </div> --}}


        <!-- Kartice -->
        <div class="flex flex-wrap -mx-3 text-naslov">
            @foreach ($knjige as $knjiga)
                <!-- Card -->
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/4 mb-4 px-3 relative">
                    <!-- Card Container -->
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-full relative z-0">
                        <!-- Image Section -->
                        @if ($knjiga->artikal->artikalSlike->isNotEmpty())
                            <img src="{{ asset('storage/img/' . $knjiga->artikal->artikalSlike->first()->naziv_fajla) }}"
                                class="w-full h-72 object-contain" alt="{{ $knjiga->artikal->naziv }}">
                        @else
                            <img src="{{ asset('storage/img/fajl-nije-pronadjen.png') }}"
                                class="w-full h-72 object-contain" alt="{{ __('Fajl nije pronađen') }}">
                        @endif

                        <!-- Book Information Section -->
                        <div class="p-4 flex-grow">
                            <h5 class="text-lg font-bold text-center">{{ $knjiga->artikal->naziv }}</h5>
                            <p class="text-tekst mt-2">
                                {{ $knjiga->artikal->kratkiOpis }}
                            </p>
                        </div>

                        <!-- Price and Add to Cart Section -->
                        <div class="mt-auto px-4 pb-4 relative z-10">
                            <div class="flex justify-between items-center space-y-4 sm:space-y-0 flex-col sm:flex-row">
                                <!-- Price Display -->
                                <div class="flex items-center text-lg mb-4 sm:mb-0">
                                    <i class="fa-solid fa-tag text-sm text-tekst mr-1"></i>
                                    @if ($knjiga->artikal->akcijska_cijena)
                                        <!-- Original Price with Strikethrough -->
                                        <span class="text-xl font-semibold text-red-600 line-through mr-2">
                                            {{ $knjiga->artikal->formatiranaCijena }}
                                        </span>
                                        <!-- Discounted Price -->
                                        <span class="text-xl font-bold text-naslov">
                                            {{ $knjiga->artikal->formatiranaAkcijskaCijena }}
                                        </span>
                                    @else
                                        <!-- Regular Price -->
                                        <span class="text-xl font-bold">
                                            {{ $knjiga->artikal->formatiranaCijena }}
                                        </span>
                                    @endif
                                    <span class="ml-1 text-sm font-semibold text-tekst">EUR</span>
                                </div>
                                <!-- Add To Cart Link -->
                                <div class="w-full sm:w-auto">
                                    <a href="" data-artikal-id="{{ $knjiga->artikal->id }}"
                                        class="add-to-cart bg-green-500 text-white text-xl hover:bg-green-600 transition w-full px-4 py-2 rounded-lg flex items-center justify-center"
                                        aria-label="{{ __('Dodaj u korpu') }}">
                                        <i class="fa-solid fa-cart-plus text-lg mr-2" aria-hidden="true"></i>
                                        <span class="text-lg">{{ __('Dodaj') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Clickable Overlay -->
                        <a href="/knjige/knjiga/{{ $knjiga->artikal_id }}" class="absolute inset-0 z-0"></a>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Kraj Kartica -->

        <!-- Linkovi za paginaciju -->
        <div class="m-12 px-12">
            {{ $knjige->links() }}
        </div>
    </div>
</x-app-layout>
