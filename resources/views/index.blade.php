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
                    <x-slide src="{{ asset('img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
                        citat="{{ __('Neki citat iz knjige') }}" autor="{{ __('Ime autora') }}"
                        naziv="{{ __('Naziv knjige') }}" />

                    <x-slide src="{{ asset('img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
                        citat="{{ __('Neki citat iz knjige') }}" autor="{{ __('Ime autora') }}"
                        naziv="{{ __('Naziv knjige') }}" />

                    <x-slide src="{{ asset('img/prva-slika.jpg') }}" alt="{{ __('Slajd 1') }}"
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
        <div class="container mx-auto">
            <div class="flex justify-center mb-10">
                <x-naslov-sekcije>{{ __('Preporučujemo') }}</x-naslov-sekcije>
            </div>
        </div>
        <!-- Kartice -->
        <div class="flex flex-wrap justify-between mx-3">
            <x-preporucujemo-card src="{{ asset('img/card.jpg') }}" alt="{{ __('Knjiga') }}"
                naslov="{{ __('Naslov knjige') }}"
                opis="{{ __('This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.') }}"
                knjigaLink="#" cijena="1500,00" valuta="EUR" />
            <x-preporucujemo-card src="{{ asset('img/card.jpg') }}" alt="{{ __('Knjiga') }}"
                naslov="{{ __('Naslov knjige') }}"
                opis="{{ __('This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.') }}"
                knjigaLink="#" cijena="1500,00" valuta="EUR" />
            <x-preporucujemo-card src="{{ asset('img/card.jpg') }}" alt="{{ __('Knjiga') }}"
                naslov="{{ __('Naslov knjige') }}"
                opis="{{ __('This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.') }}"
                knjigaLink="#" cijena="1500,00" valuta="EUR" />
            <x-preporucujemo-card src="{{ asset('img/card.jpg') }}" alt="{{ __('Knjiga') }}"
                naslov="{{ __('Naslov knjige') }}"
                opis="{{ __('This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.') }}"
                knjigaLink="#" cijena="1500,00" valuta="EUR" />
        </div>
        <!-- Kraj kartica -->
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
                                    class="text-3xl font-black text-red-500 cijena-knjigaGodine">{{ $formatiranaAkcijskaCijena }}</span>
                                <span
                                    class="ml-2 text-xl uppercase font-extrabold text-red-500">{{ __('EUR') }}</span>
                            @else
                                <!-- Display Regular Price -->
                                <span
                                    class="text-3xl font-black text-red-500 cijena-knjigaGodine">{{ $formatiranaCijena }}</span>
                                <span
                                    class="ml-2 text-xl uppercase font-extrabold text-red-500">{{ __('EUR') }}</span>
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
                                src="{{ 'img/' . $knjigaGodine->artikal->artikalSlike->first()->naziv_fajla }}"
                                alt="{{ __('Knjiga godine') }}">
                        @else
                            <img class="knjigaGodine-img object-cover w-full h-auto"
                                src="{{ asset('img/fajl-nije-pronadjen.png') }}" alt="{{ __('Knjiga godine') }}">
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
