@props(['showActions' => true])

<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead>
            <tr class="bg-gray-800 text-white">
                {{-- Za naslove tabele --}}
                {{ $thead }}
                @if ($showActions)
                    <th class="py-2 px-4">{{ __('Akcije') }}</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-tekst font-semibold">
            {{-- Za redove tabele --}}
            {{ $slot }}
            @if ($slot->isEmpty())
                <tr>
                    <td colspan="{{ $showActions ? '100%' : '100%' }}" class="py-4 px-6 text-center">
                        {{ __('Nema redova za prikaz.') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
