<x-app-layout>
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-[3rem] rounded relative my-6"
            role="alert">
            <strong class="font-bold">{{ __('Greška!') }}</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if (count($stavkePorudzbine) > 0)
        <div
            class="flex flex-wrap justify-center lg:justify-between m-auto space-y-6 lg:space-y-0 text-tekst py-12 px-11 cart">
            <div class="w-full lg:w-8/12">

                @php
                    dump(session()->get('cart'));
                @endphp
                <div class="flex justify-center">
                    <div class="w-full lg:w-10/12">
                        <x-naslov-sekcije class="mb-4 mt-2">{{ __('Korpa') }}</x-naslov-sekcije>
                        <table class="table-auto w-full text-left">
                            <thead>
                                <tr class="border-b text-naslov">
                                    <th>{{ __('Knjiga') }}</th>
                                    <th>{{ __('Cijena') }}</th>
                                    <th>{{ __('Količina') }}</th>
                                    <th>{{ __('Ukupno') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stavkePorudzbine as $stavka)
                                    <tr class="border-b"
                                        id="stavka-row-{{ Auth::check() ? $stavka->artikal->id : $stavka['artikal_id'] }}">
                                        <td class="text-tekst text-lg font-semibold">
                                            {{ Auth::check() ? $stavka->artikal->naziv : $stavka['naziv'] }}
                                        </td>
                                        <td class="text-tekst text-lg font-semibold">
                                            {{ Auth::check() ? $stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena : $stavka['formatirana_cijena'] }}
                                        </td>
                                        <td>
                                            <div class="flex items-center space-x-2">
                                                <a href="" class="px-3 py-1 decrement-btn"
                                                    data-artikal-id="{{ Auth::check() ? $stavka->artikal->id : $stavka['artikal_id'] }}">
                                                    <i class="fa-solid fa-minus"></i>
                                                </a>
                                                <input
                                                    class="w-16 text-tekst text-lg font-semibold text-center border-0 bg-gray-100 focus:outline-none focus:ring-0"
                                                    type="text"
                                                    value="{{ Auth::check() ? $stavka->kolicina : $stavka['kolicina'] }}"
                                                    readonly>

                                                <a href="" class="px-3 py-1 increment-btn"
                                                    data-artikal-id="{{ Auth::check() ? $stavka->artikal->id : $stavka['artikal_id'] }}">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td id="stavka-total-{{ Auth::check() ? $stavka->id : $loop->index }}"
                                            class="text-tekst text-lg font-semibold">
                                            {{ Auth::check() ? $stavka->ukupna_cijena : $stavka['formatirana_ukupna_cijena'] }}
                                        </td>
                                        <td class="text-center">
                                            <a href="" class="delete-btn text-red-600 hover:text-red-700"
                                                data-artikal-id="{{ Auth::check() ? $stavka->artikal->id : $stavka['artikal_id'] }}">
                                                <i class="fa-solid fa-trash-can text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-3/12 bg-[#f9f9f9] py-8 px-12 rounded-lg">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-extrabold pb-2 border-b border-gray-300">{{ __('Vaša porudžbina') }}
                    </h3>
                </div>

                <div class="text-center mb-6">
                    <p class="uppercase text-sm font-bold mb-2">{{ __('Ukupna cijena:') }}</p>
                    <span id="cart-total" class="text-3xl font-bold text-naslov">
                        {{ $formatiranaCijenaPorudzbine }}
                    </span>
                    <span class="text-sm font-extrabold text-naslov uppercase">{{ __('eur') }} </span>
                </div>

                <div class="text-center mb-6">
                    <h4 class="text-xl font-semibold mb-1">{{ __('Kod za popust') }}</h4>
                    <div class="flex">
                        <input class="w-3/4 p-3 rounded-l border-[#aeadad] text-base uppercase" type="text"
                            placeholder="POPUST100">
                        <input type="submit"
                            class="bg-green-600 text-white px-5 py-2 cursor-pointer rounded-r hover:bg-green-700 transition text-lg font-bold"
                            value="OK">
                    </div>
                </div>

                <div class="text-center mb-2">
                    <a href="{{ url('/set-delivery-step') }}"
                        class="nastavi-btn bg-yellow-500 text-white px-6 py-3 rounded w-full inline-block hover:bg-yellow-600 transition text-lg font-bold">
                        <span>{{ __('Nastavi') }} <i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                </div>

                <div class="text-center">
                    <a href="{{ url('/knjige') }}"
                        class="bg-blue-500 text-white px-6 py-3 rounded w-full inline-block hover:bg-blue-600 transition text-lg font-bold">
                        <span><i class="fa-solid fa-arrow-left"></i> {{ __('Zatvori') }}</span>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center my-[11rem]">
            <h3 class="text-center text-2xl text-naslov font-extrabold">{{ __('Vaša korpa je prazna.') }}</h3>
            <a href="{{ url('/') }}"
                class="text-blue-500 hover:underline text-xl font-bold mt-3 block">{{ __('Početna strana') }}</a>
        </div>
    @endif
</x-app-layout>
