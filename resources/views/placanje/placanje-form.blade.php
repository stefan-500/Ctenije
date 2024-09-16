<x-app-layout>
    <div class="container mx-auto my-12 px-4">
        <div class="max-w-7xl mx-auto bg-white p-8 shadow-lg rounded-lg text-tekst">
            <x-naslov-sekcije class="text-center mb-9">{{ __('Plaćanje') }}</x-naslov-sekcije>

            @if ($porudzbina)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Order Summary -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-4 text-naslov">{{ __('Pregled porudžbine') }}</h2>
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-gray-100 font-semibold">
                                <tr>
                                    <th scope="col" class="py-3 px-4 text-left">{{ __('Sifra') }}</th>
                                    <th scope="col" class="py-3 px-4">{{ __('Artikal') }}</th>
                                    <th scope="col" class="py-3 px-4 text-left">{{ __('Cijena') }}</th>
                                    <th scope="col" class="py-3 px-4 text-center">{{ __('Količina') }}</th>
                                    <th scope="col" class="py-3 px-4 text-right">{{ __('Ukupna cijena') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($porudzbina->stavkePorudzbine as $stavka)
                                    <tr class="border-b border-gray-200 font-semibold">
                                        <td class="py-4 px-4">{{ $stavka->artikal->id }}
                                        <td class="py-4 px-4">{{ $stavka->artikal->naziv }}</td>
                                        <td>{{ formatirajCijenu($stavka->artikal->akcijska_cijena ?? $stavka->artikal->cijena) }}
                                            EUR</td>
                                        <td class="py-4 px-4 text-center">{{ $stavka->kolicina }}</td>
                                        <td class="py-4 px-4 text-right">
                                            {{ formatirajCijenu($stavka->ukupna_cijena) }} EUR</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="flex justify-between items-center mt-6">
                            <span class="text-lg font-medium ">{{ __('Ukupno za naplatu:') }}</span>
                            <span class="text-2xl font-bold text-green-600">{{ formatirajCijenu($porudzbina->ukupno) }}
                                EUR</span>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-4 text-naslov">{{ __('Podaci o plaćanju') }}</h2>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                            <!-- Trust Badge -->
                            <div class="flex items-center mb-4">
                                <i class="fa-solid fa-lock text-green-600 mr-2"></i>
                                <p class="text-sm">{{ __('Sigurno plaćanje putem') }}
                                    <strong>{{ __('  Stripe-a') }}</strong>
                                </p>
                            </div>

                            <form id="payment-form">
                                @csrf

                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Cardholder Name -->
                                    <div>
                                        <label for="cardholder-name" class="block text-sm font-medium">
                                            {{ __('Ime na kartici') }}
                                        </label>
                                        <input id="cardholder-name" type="text" required autofocus
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="{{ __('Unesite ime i prezime') }}">
                                    </div>

                                    <!-- Card Number with FontAwesome Icon -->
                                    <div>
                                        <label for="card-number-element" class="block text-sm font-medium">
                                            {{ __('Broj kartice') }}
                                        </label>
                                        <div class="relative mt-1">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-credit-card text-gray-400"></i>
                                            </div>
                                            <div id="card-number-element"
                                                class="pl-10 p-3 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <!-- Card Number Element will be inserted here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Expiration Date and CVC -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <!-- Expiration Date -->
                                        <div>
                                            <label for="card-expiry-element" class="block text-sm font-medium">
                                                {{ __('Datum isteka') }}
                                            </label>
                                            <div id="card-expiry-element"
                                                class="mt-1 p-3 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <!-- Card Expiry Element will be inserted here -->
                                            </div>
                                        </div>

                                        <!-- CVC -->
                                        <div>
                                            <label for="card-cvc-element" class="block text-sm font-medium">
                                                {{ __('CVC kod') }}
                                            </label>
                                            <div id="card-cvc-element"
                                                class="mt-1 p-3 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <!-- Card CVC Element will be inserted here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Message -->
                                    <div>
                                        <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                                    </div>
                                </div>

                                <!-- Include payment token if it's not null -->
                                @if ($paymentToken)
                                    <input type="hidden" id="payment_token" value="{{ $paymentToken }}">
                                @endif

                                <button id="submit-button"
                                    class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center">
                                    <svg id="spinner" class="hidden animate-spin h-5 w-5 mr-3 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span id="button-text">{{ __('Plati') }}</span>
                                </button>
                                <button id="cancel-button" onclick="otkaziPlacanje()"
                                    class="w-full mt-4 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out flex items-center justify-center">
                                    {{ __('Odustani') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-tekst">{{ __('Nema porudžbine za prikaz.') }}</p>
            @endif
        </div>
    </div>

    <!-- Include Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Initialize Stripe
        var stripe = Stripe('{{ $stripeKey }}');
        var elements = stripe.elements();

        // Custom styling for the Elements
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Roboto", "Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#a0aec0',
                }
            },
            invalid: {
                color: '#e53e3e',
                iconColor: '#e53e3e'
            }
        };

        // Create individual Elements
        var cardNumber = elements.create('cardNumber', {
            style: style,
            placeholder: '{{ __('Unesite broj kartice') }}'
        });
        cardNumber.mount('#card-number-element');

        var cardExpiry = elements.create('cardExpiry', {
            style: style,
            placeholder: '{{ __('MM / GG') }}'
        });
        cardExpiry.mount('#card-expiry-element');

        var cardCvc = elements.create('cardCvc', {
            style: style,
            placeholder: '{{ __('CVC') }}'
        });
        cardCvc.mount('#card-cvc-element');

        // Handle real-time validation errors
        var errorElement = document.getElementById('card-errors');

        [cardNumber, cardExpiry, cardCvc].forEach(function(element) {
            element.on('change', function(event) {
                if (event.error) {
                    errorElement.textContent = event.error.message;
                } else {
                    errorElement.textContent = '';
                }
            });
        });


        function otkaziPlacanje() {
            var paymentIntentId = '{{ $paymentIntentId }}';

            fetch('{{ route('placanje.otkazi') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntentId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        window.location.href = '{{ route('placanje.otkazano') }}';
                    } else {
                        alert(data.error || 'Došlo je do greške prilikom otkazivanja plaćanja.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Došlo je do greške prilikom otkazivanja plaćanja.');
                });
        }



        // Apply focus and blur event handlers to Stripe Elements to add outline
        function setFocusStyles(element, containerId) {
            element.on('focus', function() {
                document.getElementById(containerId).classList.add('ring', 'ring-indigo-500', 'border-indigo-500');
            });
            element.on('blur', function() {
                document.getElementById(containerId).classList.remove('ring', 'ring-indigo-500',
                    'border-indigo-500');
            });
        }

        setFocusStyles(cardNumber, 'card-number-element');
        setFocusStyles(cardExpiry, 'card-expiry-element');
        setFocusStyles(cardCvc, 'card-cvc-element');

        // Handle form submission
        var form = document.getElementById('payment-form');
        var submitButton = document.getElementById('submit-button');
        var spinner = document.getElementById('spinner');
        var buttonText = document.getElementById('button-text');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            submitButton.disabled = true;
            buttonText.textContent = '{{ __('Obrada...') }}';
            spinner.classList.remove('hidden');

            var cardholderName = document.getElementById('cardholder-name').value;

            stripe.confirmCardPayment('{{ $clientSecret }}', {
                payment_method: {
                    card: cardNumber,
                    billing_details: {
                        name: cardholderName,
                        email: '{{ Auth::check() ? Auth::user()->email : $porudzbina->guestDeliveryData->email }}'
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Prikaz greske
                    errorElement.textContent = result.error.message;
                    submitButton.disabled = false;
                    buttonText.textContent = '{{ __('Plati') }}';
                    spinner.classList.add('hidden');
                } else {
                    // Placanje uspijesno!
                    var paymentData = {
                        payment_intent_id: result.paymentIntent.id,
                        _token: '{{ csrf_token() }}'
                    };

                    // Dodavanje payment tokena za neprijavljenog korisnika
                    @if ($paymentToken)
                        paymentData.payment_token = '{{ $paymentToken }}';
                    @endif

                    fetch('{{ url('/placanje') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify(paymentData),
                    }).then(function(response) {
                        return response.json();
                    }).then(function(responseJson) {
                        if (responseJson.success) {
                            if (responseJson.message) { // Ako email nije poslat
                                alert(responseJson.message);
                            }
                            window.location.href = '{{ route('placanje.uspijeh') }}';
                        } else {
                            // Serverske greske
                            errorElement.textContent = responseJson.error;
                            submitButton.disabled = false;
                            buttonText.textContent = '{{ __('Plati') }}';
                            spinner.classList.add('hidden');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
