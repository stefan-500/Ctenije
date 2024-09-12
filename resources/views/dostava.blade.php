<x-app-layout>
    <div class="container mx-auto my-12">
        <form action="#" method="post" name="formPlacanje" class="w-full max-w-lg mx-auto">
            <div class="flex flex-col justify-center">
                <div class="text-center mb-6">
                    <x-naslov-sekcije class="mb-3">{{ __('Dostava') }}</x-naslov-sekcije>
                </div>

                <!-- Ime Prezime -->
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
                            for="ime">{{ __('Ime') }}</label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                            id="ime" type="text" placeholder="{{ __('Zika') }}" name="ime"
                            value="{{ old('ime', $user->ime ?? '') }}">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
                            for="prezime">{{ __('Prezime') }}</label>
                        <input
                            class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                            id="prezime" type="text" placeholder="{{ __('Zikic') }}" name="prezime"
                            value="{{ old('prezime', $user->prezime ?? '') }}">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
                        for="mejl">{{ __('Mejl adresa') }}</label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                        id="mejl" type="email" placeholder="{{ __('zika@mejl.com') }}" name="mejl"
                        value="{{ old('mejl', $user->email ?? '') }}">
                </div>

                <!-- Kontakt -->
                <div class="mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
                        for="tel">{{ __('Kontakt') }}</label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                        id="tel" type="text" placeholder="{{ __('123-456-789') }}" name="tel"
                        value="{{ old('tel', $user->tel ?? '') }}">
                </div>

                <!-- Addresa -->
                <div class="mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
                        for="adresa">{{ __('Adresa za isporuku') }}</label>
                    <input
                        class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                        id="adresa" type="text" placeholder="{{ __('Vuka Karadžića 56, 11000 Beograd') }}"
                        name="adresa" value="{{ old('adresa', $user->adresa ?? '') }}">
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Potvrdi') }}
                    </button>
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
