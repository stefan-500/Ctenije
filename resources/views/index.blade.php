<x-app-layout>

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-[3rem] rounded relative my-6"
            role="alert">
            <strong class="font-bold">{{ __('Greška!') }}</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    {{-- Slajder --}}
    <section class="section slajder">
        <div id="carouselExampleCaptions" class="relative">
            <div class="relative overflow-hidden">
                <!-- Carousel Inner -->
                <div class="flex transition-transform duration-700 ease-in-out transform" id="carousel-inner">
                    <x-slide src="{{ asset('storage/img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
                        citat="{{ __('Neki citat iz knjige') }}" autor="{{ __('Ime autora') }}"
                        naziv="{{ __('Naziv knjige') }}" />

                    <x-slide src="{{ asset('storage/img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
                        citat="{{ __('Neki citat iz knjige') }}" autor="{{ __('Ime autora') }}"
                        naziv="{{ __('Naziv knjige') }}" />

                    <x-slide src="{{ asset('storage/img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
                        citat="{{ __('Neki citat iz knjige') }}" autor="{{ __('Ime autora') }}"
                        naziv="{{ __('Naziv knjige') }}" />
                </div>
                <!-- Indicators -->
                <ol
                    class="absolute top-2 sm:top-4 left-1/2 transform -translate-x-1/2 flex justify-center p-0 mb-4 space-x-2">
                    <x-indicator class="opacity-100" data-slide-to="0" />
                    <x-indicator class="opacity-50" data-slide-to="1" />
                    <x-indicator class="opacity-50" data-slide-to="2" />
                </ol>

                <!-- Kontrole -->
                <x-slider-button class="left-2" />
                <x-slider-button id="nextBtn" class="right-2" iconClass="fas fa-chevron-right" />
            </div>
        </div>
    </section>
    {{-- Kraj Slajdera --}}
    {{-- Preporuceno --}}
    <section class="section preporuceno py-12">
        <div class="container mx-auto px-3">
            <div class="flex justify-center mb-10">
                <x-naslov-sekcije>{{ __('Preporučujemo') }}</x-naslov-sekcije>
            </div>

            <!-- Kartice -->
            <div class="flex flex-wrap -mx-3 text-naslov">
                @foreach ($preporuceneKnjige as $knjiga)
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
                                <div
                                    class="flex justify-between items-center space-y-4 sm:space-y-0 flex-col sm:flex-row">
                                    <!-- Price Display -->
                                    <div class="flex items-center text-lg mb-4 sm:mb-0">
                                        <i class="fa-solid fa-tag text-sm text-tekst mr-1"></i>
                                        @if ($knjiga->artikal->formatiranaAkcijskaCijena)
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
        </div>

    </section>
    {{-- Kraj Preporuceno --}}
    {{-- Knjiga godine  --}}
    <section class="knjigaGodine py-12 px-4">
        <div class="flex flex-wrap">
            <!-- Left Column -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-end">
                <div class="flex justify-end mb-8">
                    <x-naslov-sekcije>{{ __('Knjiga godine') }}</x-naslov-sekcije>
                </div>
                <div class="flex justify-end mb-2">
                    <h1 class="text-4xl font-black uppercase text-naslov">{{ $knjigaGodine->artikal->naziv }}</h1>
                </div>
                <div class="flex flex-col justify-end space-y-4 items-end">
                    <p class="text-lg text-right text-tekst">{{ $opis }}</p>
                    <h5 class="text-xl font-extrabold w-full text-right uppercase text-naslov">
                        {{ $knjigaGodine->autor }}</h5>
                    <div class="w-full text-right">
                        <p class="text-xl font-semibold">
                            @if ($knjigaGodine->artikal->akcijska_cijena)
                                <span class="text-xl line-through text-gray-500">{{ $formatiranaCijena }}</span>
                                <span class="ml-2 text-sm font-bold text-gray-500 uppercase">{{ __('EUR') }}</span>
                                <br>
                                <span
                                    class="text-3xl font-black text-red-700 cijena-knjigaGodine">{{ $formatiranaAkcijskaCijena }}</span>
                                <span
                                    class="ml-2 text-xl uppercase font-extrabold text-red-700">{{ __('EUR') }}</span>
                            @else
                                <!-- Display Regular Price -->
                                <span
                                    class="text-3xl font-black text-red-700 cijena-knjigaGodine">{{ $formatiranaCijena }}</span>
                                <span
                                    class="ml-2 text-xl uppercase font-extrabold text-red-700">{{ __('EUR') }}</span>
                            @endif
                        </p>
                    </div>
                    <a href="knjige/knjiga/{{ $knjigaGodine->artikal_id }}"
                        class="border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white transition px-4 py-2 rounded">{{ __('Pročitaj više') }}</a>
                </div>
            </div>

            <!-- Right Column -->
            <div class="w-full md:w-1/2 flex justify-center">
                <figure class="figure-knjigaGodine">
                    <a href="knjige/knjiga/{{ $knjigaGodine->artikal_id }}">
                        @if ($knjigaGodine->artikal->artikalSlike->isNotEmpty())
                            <img class="knjigaGodine-img object-cover w-full h-auto"
                                src="{{ 'storage/img/' . $knjigaGodine->artikal->artikalSlike->first()->naziv_fajla }}"
                                alt="{{ __('Knjiga godine') }}">
                        @else
                            <img class="knjigaGodine-img object-cover w-full h-auto"
                                src="{{ asset('storage/img/fajl-nije-pronadjen.png') }}"
                                alt="{{ __('Knjiga godine') }}">
                        @endif
                    </a>
                </figure>
            </div>
        </div>
    </section>

    {{-- Kraj knjige godine --}}
    {{-- Kontakt forma --}}
    <section class="section-contact py-12">
        <div class="container mx-auto px-4">
            <div class="flex justify-center">
                <div class="w-full md:w-2/3 lg:w-1/3">
                    <form action="#" class="rounded px-6 pt-6 pb-8 mb-4">
                        <div class="flex justify-center mb-8">
                            <x-naslov-sekcije>{{ __('Kontaktirajte nas') }}</x-naslov-sekcije>
                        </div>
                        <div class="mb-4">
                            <label for="mejl"
                                class="block text-m text-tekst font-semibold mb-2">{{ __('Mejl adresa') }}</label>
                            <input type="email" id="mejl" placeholder="{{ __('ime.prezime@domen.tld') }}"
                                class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent">
                        </div>
                        <div class="mb-6">
                            <label for="poruka"
                                class="block text-m text-tekst font-semibold mb-2">{{ __('Poruka') }}</label>
                            <textarea id="poruka" rows="6" placeholder="{{ __('Polje za poruku') }}"
                                class="shadow-sm border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent resize-none"></textarea>
                        </div>
                        <div class="flex items-center justify-center">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent">
                                {{ __('Pošalji') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    {{-- Kraj kontakt forme --}}
    {{-- Javascript za slajder --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carouselInner = document.getElementById('carousel-inner');
            const slides = carouselInner.children;
            const indicators = document.querySelectorAll('[data-slide-to]');
            let currentIndex = 0;

            function showSlide(index) {
                const offset = -index * 100; // Adjust based on the slide width
                carouselInner.style.transform = `translateX(${offset}%)`;

                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.add('bg-gray-200', 'opacity-100');
                        indicator.classList.remove('bg-gray-400', 'opacity-50');
                    } else {
                        indicator.classList.add('bg-gray-400', 'opacity-50');
                        indicator.classList.remove('bg-gray-200', 'opacity-100');
                    }
                });
            }

            document.getElementById('prevBtn').addEventListener('click', function() {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
                showSlide(currentIndex);
            });

            document.getElementById('nextBtn').addEventListener('click', function() {
                currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
                showSlide(currentIndex);
            });

            indicators.forEach((indicator, i) => {
                indicator.addEventListener('click', () => {
                    currentIndex = i;
                    showSlide(currentIndex);
                });
            });

            // Auto-slide
            setInterval(() => {
                document.getElementById('nextBtn').click();
            }, 8000); // 8 seconds

            // Swipe za manje ekrane
            let startX = 0;

            carouselInner.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
            });

            carouselInner.addEventListener('touchend', function(e) {
                const endX = e.changedTouches[0].clientX;
                if (startX > endX + 50) {
                    document.getElementById('nextBtn').click();
                } else if (startX < endX - 50) {
                    document.getElementById('prevBtn').click();
                }
            });
        });
    </script>
</x-app-layout>
