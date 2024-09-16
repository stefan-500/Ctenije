<x-app-layout>
    <div class="container mx-auto my-12">
        <form action="{{ url('/dostava') }}" method="post" name="formPlacanje" class="w-full max-w-lg mx-auto">
            @csrf
            <div class="flex flex-col justify-center">
                <div class="text-center mb-6">
                    <x-naslov-sekcije class="mb-3">{{ __('Dostava') }}</x-naslov-sekcije>
                </div>

                @guest
                    <!-- Ime Prezime -->
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1"
                                for="ime">{{ __('Ime') }}</label>
                            <input
                                class="appearance-none block w-full bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white placeholder:font-light font-semibold"
                                id="ime" type="text" {{-- placeholder="{{ __('Zika') }}" --}} name="ime"
                                value="{{ old('ime', $guestDeliveryData['ime'] ?? '') }}" required>
                            @error('ime')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1"
                                for="prezime">{{ __('Prezime') }}</label>
                            <input
                                class="appearance-none block w-full bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white placeholder:font-light font-semibold"
                                id="prezime" type="text" {{-- placeholder="{{ __('Zikic') }}" --}} name="prezime"
                                value="{{ old('prezime', $guestDeliveryData['prezime'] ?? '') }}" required>
                            @error('prezime')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1"
                            for="email">{{ __('Email adresa') }}</label>
                        <input
                            class="appearance-none block w-full bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white placeholder:font-light font-semibold"
                            id="email" type="email" {{-- placeholder="{{ __('zika@mejl.com') }}" --}} name="email"
                            value="{{ old('email', $guestDeliveryData['email'] ?? '') }}" required>
                        @error('email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontakt -->
                    <div class="mb-6">
                        <label class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1"
                            for="tel">{{ __('Kontakt') }}</label>
                        <input
                            class="appearance-none block w-full bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white placeholder:font-light font-semibold"
                            id="tel" type="text" {{-- placeholder="{{ __('123-456-789') }}" --}} name="tel"
                            value="{{ old('tel', $guestDeliveryData['tel'] ?? '') }}" required>
                        @error('tel')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                @endguest

                <!-- Adresa -->
                <div class="mb-6">
                    <label class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1"
                        for="adresa">{{ __('Adresa za isporuku') }}</label>
                    @guest
                        <input
                            class="appearance-none block w-full bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white placeholder:font-light font-semibold"
                            id="adresa" type="text" name="adresa"
                            value="{{ old('adresa', $guestDeliveryData['adresa'] ?? '') }}" required>
                        @error('adresa')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    @else
                        <p class="bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight">
                            {{ $user->adresa }}
                        </p>
                    @endguest
                </div>

                <!-- Kontakt, Email za prijavljenog korisnika -->
                @auth
                    <div class="mb-6">
                        <label
                            class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1">{{ __('Kontakt') }}</label>
                        <p class="bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight">
                            {{ $user->tel }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label
                            class="block uppercase tracking-wide text-tekst text-xs font-bold mb-1">{{ __('Mejl adresa') }}</label>
                        <p class="bg-[#f8f8f8] text-tekst border border-gray-300 rounded py-3 px-4 leading-tight">
                            {{ $user->email }}
                        </p>
                    </div>
                @endauth

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white text-center font-bold py-3 rounded focus:outline-none focus:shadow-outline">
                        {{ __('Nastavi na plaćanje') }}
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
