{{-- resources/views/admin/artikli/izmijeni.blade.php --}}

<x-admin-layout>
    <div class="max-w-4xl mx-auto p-8">
        <x-naslov-sekcije class="text-center mb-8">{{ __('Izmijeni Knjigu') }}</x-naslov-sekcije>

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

        {{-- Edit Book Form --}}
        <form action="{{ url('/admin/artikli/izmijeni/' . $artikal->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 text-tekst">
            @csrf
            @method('PUT')

            {{-- Naziv and ISBN --}}
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="naziv" class="block text-sm font-medium">{{ __('Naziv') }}</label>
                    <input type="text" name="naziv" id="naziv" value="{{ old('naziv', $artikal->naziv) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('naziv') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite naziv knjige') }}" required>
                    @error('naziv')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2 px-3">
                    <span class="block text-sm font-medium">{{ __('ISBN') }}</span=>
                        <p
                            class="bg-[#f8f8f8] text-tekst text-base border border-gray-300 py-2.5 mt-1 px-3 rounded-md leading-tight">
                            {{ $artikal->knjiga->isbn }}
                        </p>
                </div>
            </div>

            {{-- Opis --}}
            <div>
                <label for="opis" class="block text-sm font-medium">{{ __('Opis') }}</label>
                <textarea name="opis" id="opis" rows="8"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('opis') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite opis knjige') }}">{{ old('opis', $artikal->opis) }}</textarea>
                @error('opis')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Autor and Izdavac --}}
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="autor" class="block text-sm font-medium">{{ __('Autor') }}</label>
                    <input type="text" name="autor" id="autor"
                        value="{{ old('autor', $artikal->knjiga->autor) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('autor') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite autora') }}" required>
                    @error('autor')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2 px-3">
                    <label for="izdavac" class="block text-sm font-medium">{{ __('Izdavač') }}</label>
                    <input type="text" name="izdavac" id="izdavac"
                        value="{{ old('izdavac', $artikal->knjiga->izdavac) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('izdavac') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite izdavača') }}" required>
                    @error('izdavac')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>
            </div>

            {{-- Izdanje and Broj Stranica --}}
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="izdanje" class="block text-sm font-medium">{{ __('Izdanje') }}</label>
                    <input type="text" name="izdanje" id="izdanje"
                        value="{{ old('izdanje', $artikal->knjiga->izdanje) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('izdanje') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite izdanje') }}" required>
                    @error('izdanje')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2 px-3">
                    <label for="br_stranica" class="block text-sm font-medium">{{ __('Broj Stranica') }}</label>
                    <input type="text" name="br_stranica" id="br_stranica"
                        value="{{ old('br_stranica', $artikal->knjiga->br_stranica) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('br_stranica') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite broj stranica') }}" required>
                    @error('br_stranica')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pismo --}}
            <div>
                <label for="pismo" class="block text-sm font-medium">{{ __('Pismo') }}</label>
                <select name="pismo" id="pismo"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('pismo') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">{{ __('Izaberite pismo') }}</option>
                    <option value="Ćirilica"
                        {{ old('pismo', $artikal->knjiga->pismo) == 'Ćirilica' ? 'selected' : '' }}>
                        {{ __('Ćirilica') }}
                    </option>
                    <option value="Latinica"
                        {{ old('pismo', $artikal->knjiga->pismo) == 'Latinica' ? 'selected' : '' }}>
                        {{ __('Latinica') }}
                    </option>
                </select>
                @error('pismo')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Slika (Image) --}}
            <div>
                <label for="slika" class="block text-sm font-medium">{{ __('Slika') }}</label>
                <input type="file" name="slika" id="slika" accept="image/*"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('slika') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                @error('slika')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror

                {{-- Current Image Preview --}}
                @if ($artikal->artikalSlike->isNotEmpty())
                    <div class="mt-4">
                        <span
                            class=">{{ __('Trenutna Slika:') }}</span>
                        <img src="{{ asset('storage/img/' . $artikal->artikalSlike->first()->naziv_fajla) }}"
                            alt="{{ $artikal->naziv }}" class="mt-2 w-64 h-64 object-cover rounded">
                    </div>
                @endif
            </div>

            {{-- Cijena and Akcijska Cijena --}}
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="cijena" class="block text-sm font-medium">{{ __('Cijena(cijeli broj)') }}</label>
                    <input type="text" name="cijena" id="cijena" value="{{ old('cijena', $artikal->cijena) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('cijena') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite cijenu') }}" required>
                    @error('cijena')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2 px-3">
                    <label for="akcijska_cijena"
                        class="block text-sm font-medium">{{ __('Akcijska Cijena(cijeli broj)') }}</label>
                    <input type="text" name="akcijska_cijena" id="akcijska_cijena"
                        value="{{ old('akcijska_cijena', $artikal->akcijska_cijena) }}"
                        class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('akcijska_cijena') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="{{ __('Unesite akcijsku cijenu (opcionalno)') }}">
                    @error('akcijska_cijena')
                        <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dostupna Količina --}}
            <div>
                <label for="dostupna_kolicina"
                    class="block text-sm font-medium">{{ __('Dostupna Količina') }}</label>
                <input type="number" name="dostupna_kolicina" id="dostupna_kolicina"
                    value="{{ old('dostupna_kolicina', $artikal->dostupna_kolicina) }}"
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('dostupna_kolicina') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="{{ __('Unesite dostupnu količinu') }}" required>
                @error('dostupna_kolicina')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Vrsta Artikala (Product Categories) --}}
            <div>
                <label for="vrsta_artikala" class="block text-sm font-medium">{{ __('Vrsta Artikala') }}</label>
                <select name="vrsta_artikala[]" id="vrsta_artikala" multiple
                    class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('vrsta_artikala') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach ($vrsteArtikala as $vrsta)
                        <option value="{{ $vrsta->id }}"
                            {{ collect(old('vrsta_artikala', $artikal->vrsteArtikla->pluck('id')->toArray()))->contains($vrsta->id) ? 'selected' : '' }}>
                            {{ __($vrsta->naziv) }}
                        </option>
                    @endforeach
                </select>
                @error('vrsta_artikala')
                    <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    {{ __('Ažuriraj') }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
