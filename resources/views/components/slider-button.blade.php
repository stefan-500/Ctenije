<button
    {{ $attributes->merge(['id' => 'prevBtn', 'class' => 'hidden sm:flex absolute top-1/2 transform -translate-y-1/2 w-1/6 h-full text-gray-400 hover:text-white transition items-center justify-center']) }}>
    <i class="{{ $iconClass ?? 'fas fa-chevron-left' }} text-3xl font-bold"></i>
    <span class="sr-only">{{ $slot ?? 'Previous' }}</span>
</button>
