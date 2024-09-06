<div class="w-full md:w-1/2 lg:w-1/4 p-3">
    <a href="{{ $knjigaLink }}"
        class=" bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-full hover:shadow-xl transition-shadow duration-300">
        <img src="{{ $src }}" class="w-full h-auto" alt="{{ $alt }}">
        <div class="p-4 flex-grow">
            <h5 class="text-lg font-bold text-tekst">{{ $naslov }}</h5>
            <p class="text-tekst">{{ $opis }}</p>
        </div>
        <div class="px-4 pb-4">
            <div class="flex justify-between items-center">
                <span href="#"
                    class="bg-green-500 text-white text-xl hover:bg-green-600 transition w-32 px-3 py-2 rounded-lg flex justify-center items-center cursor-pointer">
                    <i class="fa-solid fa-cart-plus"></i>
                </span>
                <div class="flex items-center text-lg ml-2">
                    <i class="fa-solid fa-tag text-tekst text-sm mr-1"></i>
                    <span class="font-bold text-xl text-naslov">{{ $cijena }}</span>
                    <span class="ml-1 text-sm font-semibold text-tekst">{{ $valuta }}</span>
                </div>
            </div>
        </div>
    </a>
</div>
