<div class="relative flex-shrink-0 w-full active">
    <img src="{{ $src }}" class="block w-full" alt="{{ $alt }}" />
    <!-- Tint overlay -->
    <div class="absolute inset-0 bg-gray-900 opacity-25"></div>
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 p-4 sm:p-6 text-white">
        <div class="text-center">
            <blockquote class="text-sm sm:text-lg font-semibold mb-3">{{ $citat }}
            </blockquote>
            <p class="autor text-xs sm:text-sm font-medium mb-2">{{ $autor }}</p>
            <h5 class="nazivKnjige text-base sm:text-lg font-bold">{{ $naziv }}</h5>
            <input value="Detaljnije"
                class="btn bg-blue-500 text-white py-1 sm:py-2 px-3 mt-7 sm:px-4 rounded my-2 hover:bg-blue-600 cursor-pointer transition"
                type="submit">
            </input>
        </div>
    </div>
</div>
