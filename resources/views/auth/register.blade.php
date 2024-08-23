<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Ime -->
        <div>
            <x-input-label for="ime" :value="__('Ime')" />
            <x-text-input id="ime" class="block mt-1 w-full" type="text" name="ime" :value="old('ime')" required
                autofocus autocomplete="ime" />
            <x-input-error :messages="$errors->get('ime')" class="mt-2" />
        </div>


        <!-- Prezime -->
        <div class="mt-2">
            <x-input-label for="prezime" :value="__('Prezime')" />
            <x-text-input id="prezime" class="block mt-1 w-full" type="text" name="prezime" :value="old('prezime')"
                required autofocus autocomplete="Prezime" />
            <x-input-error :messages="$errors->get('prezime')" class="mt-2" />
        </div>

        <!-- Email adresa -->
        <div class="mt-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Adresa stanovanja -->
        <div class="mt-2">
            <x-input-label for="adresa" :value="__('Adresa stanovanja')" />
            <x-text-input id="adresa" class="block mt-1 w-full" type="text" name="adresa" :value="old('adresa')"
                required autofocus autocomplete="adresa" />
            <x-input-error :messages="$errors->get('adresa')" class="mt-2" />
        </div>

        <!-- Telefon -->
        <div class="mt-2">
            <x-input-label for="tel" :value="__('Telefon')" />
            <x-text-input id="tel" class="block mt-1 w-full" type="text" name="tel" :value="old('tel')"
                required autofocus autocomplete="tel" />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-2">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-2">
            <x-input-label for="password_confirmation" :value="__('Potvrdi password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Već imate nalog?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registracija') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
