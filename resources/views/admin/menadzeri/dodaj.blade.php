{{-- resources/views/admin/menadzeri/create.blade.php --}}

<x-admin-layout>
    <div class="max-w-4xl mx-auto p-8">
        <x-naslov-sekcije class="text-center mb-8">{{ __('Dodaj Menadžera') }}</x-naslov-sekcije>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/admin/menadzeri/dodaj') }}" method="POST" class="space-y-4 text-tekst">
            @csrf

            {{-- Ime and Prezime --}}
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="ime" class="block text-sm font-semibold">{{ __('Ime') }}</label>
                    <input type="text" name="ime" id="ime" value="{{ old('ime') }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('ime') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite ime') }}" required>
                    @error('ime')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2 px-3">
                    <label for="prezime" class="block text-sm font-semibold">{{ __('Prezime') }}</label>
                    <input type="text" name="prezime" id="prezime" value="{{ old('prezime') }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('prezime') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite prezime') }}" required>
                    @error('prezime')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite email adresu') }}" required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Telefon --}}
            <div>
                <label for="tel" class="block text-sm font-semibold">{{ __('Telefon') }}</label>
                <input type="text" name="tel" id="tel" value="{{ old('tel') }}"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('tel') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite broj telefona') }}" required>
                @error('tel')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Adresa --}}
            <div>
                <label for="adresa" class="block text-sm font-semibold">{{ __('Adresa') }}</label>
                <input type="text" name="adresa" id="adresa" value="{{ old('adresa') }}"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('adresa') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite adresu') }}" required>
                @error('adresa')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Lozinka --}}
            <div>
                <label for="password" class="block text-sm font-semibold">{{ __('Lozinka') }}</label>
                <input type="password" name="password" id="password"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite lozinku') }}" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Potvrda Lozinke --}}
            <div>
                <label for="password_confirmation"
                    class="block text-sm font-semibold">{{ __('Potvrdi Lozinku') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Potvrdite lozinku') }}" required>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Submit button --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    {{ __('Dodaj') }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
