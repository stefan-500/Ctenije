<x-app-layout>

    <div class="container mx-auto px-4 py-[7rem] text-tekst">
        <!-- Row for the image and details -->
        <div class="flex flex-wrap">
            <!-- Book image -->
            <div class="w-full md:w-1/3">
                <figure class="flex justify-center">
                    <div>
                        <img class="object-contain h-94 w-full"
                            src="{{ asset('img/' . $knjiga->artikal->artikalSlike->first()->naziv_fajla) }}"
                            alt="{{ __('Knjiga') }}">
                    </div>

                </figure>
            </div>

            <div class="w-full md:w-1/3 mt-4 md:mt-0">
                <h3 class="text-2xl font-bold mb-4 pb-2 text-center text-naslov mx-auto w-2/3 border-b border-gray-300">
                    {{ $knjiga->artikal->naziv }}
                </h3>
                <div class="mt-3">

                </div>
                <p class=" mb-2">
                    <span class="font-semibold">{{ __('Autor:') }}</span>
                    <span class="text-lg font-bold">{{ $knjiga->autor }}</span>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">{{ __('Godina izdanja:') }}</span>
                    <span class="text-lg font-bold">{{ $knjiga->izdanje }}</span>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">{{ __('Izdavač:') }}</span>
                    <span class="text-lg font-bold">{{ $knjiga->izdavac }}</span>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">{{ __('Broj strana:') }}</span>
                    <span class="text-lg font-bold">{{ $knjiga->br_stranica }}</span>
                </p>
                <p class="mb-2">
                    <span class="font-semibold">{{ __('Pismo:') }}</span>
                    <span class="text-lg font-bold">{{ $knjiga->pismo }}</span>
                </p>
            </div>



            <!-- Pricing and Add to Cart button -->
            <div class="w-full md:w-1/3 mt-4 md:mt-0">
                <div class="bg-gray-100 p-4 rounded-lg shadow-md text-center">
                    <h5 class="text-xl font-bold mb-4">{{ __('Cijena:') }}</h5>

                    @if ($knjiga->artikal->akcijska_cijena)
                        <p class="text-xl font-bold text-gray-500 mb-2">
                            <span class="font-extrabold line-through"> {{ $knjiga->artikal->formatiranaCijena }}
                            </span>
                            <span class="ml-1 text-sm uppercase font-extrabold">{{ __('RSD') }}</span>
                        </p>
                        <p class="text-3xl font-bold text-red-600 mb-4">
                            <span class="font-extrabold">
                                {{ $knjiga->artikal->formatiranaAkcijskaCijena }} </span>
                            <span class="ml-1 text-xl uppercase font-extrabold">{{ __('RSD') }}</span>
                        </p>
                    @else
                        <p class="text-3xl font-bold text-red-600 mb-4">
                            <span class="font-extrabold"> {{ $knjiga->artikal->formatiranaCijena }} </span>
                            <span class="ml-1 text-xl uppercase font-extrabold">{{ __('RSD') }}</span>
                        </p>
                    @endif

                    <form action="">
                        @csrf
                        <button name="btnDodaj" type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition ease-in-out duration-150">
                            {{ __('Dodaj u korpu') }}
                        </button>
                    </form>
                </div>
            </div>


            <!-- Row for the book description -->
            <div class="flex justify-center mt-8 mx-12">
                <div class="w-full md:w-10/12">
                    <p class="text-gray-700 text-lg">{{ $knjiga->artikal->opis }}</p>
                </div>
            </div>
        </div>
</x-app-layout>
