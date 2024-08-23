<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Lični podaci') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ažurirajte lične podatke i email adresu.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="ime" :value="__('Ime')" />
            <x-text-input id="ime" name="ime" type="text" class="block w-full" :value="old('ime', $user->ime)" autofocus
                autocomplete="ime" />
            <x-input-error class="mt-2" :messages="$errors->get('ime')" />
        </div>

        <div>
            <x-input-label for="prezime" :value="__('Prezime')" />
            <x-text-input id="prezime" name="prezime" type="text" class="block w-full" :value="old('prezime', $user->prezime)" autofocus
                autocomplete="prezime" />
            <x-input-error class="mt-2" :messages="$errors->get('prezime')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Vaša email adresa nije verifikovana.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Pošalji verifikacioni mejl ponovo.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Novi verifikacioni link je poslat na vašu email adresu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="tel" :value="__('Tel')" />
            <x-text-input id="tel" name="tel" type="text" class="block w-full" :value="old('tel', $user->tel)" autofocus
                autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('tel')" />
        </div>

        <div>
            <x-input-label for="adresa" :value="__('Adresa')" />
            <x-text-input id="adresa" name="adresa" type="text" class="block w-full" :value="old('adresa', $user->adresa)" autofocus
                autocomplete="adresa" />
            <x-input-error class="mt-2" :messages="$errors->get('adresa')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Sačuvaj') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600">{{ __('Sačuvano.') }}</p>
            @endif
        </div>
    </form>
</section>
