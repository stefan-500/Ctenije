@props(['urlPrefix', 'entitet' => 'korisnika'])

@php
    $baseUrl = rtrim(url($urlPrefix), '/');
@endphp

<div x-show="showDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak
    x-transition role="dialog" aria-modal="true">
    <div class="bg-white rounded-lg p-6 w-96 shadow-lg" @click.away="showDeleteModal = false">
        <h2 class="text-xl font-semibold mb-4">{{ __('Potvrda brisanja') }}</h2>
        <p class="mb-4">
            {{ __('Da li ste sigurni da želite da obrišete') }}
            <strong x-text="itemToDelete ? itemToDelete.name : ''"></strong>
            {{ __($entitet) }}?
        </p>
        <div class="flex justify-end space-x-4">
            <button @click="showDeleteModal = false"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 focus:outline-none">
                {{ __('Otkaži') }}
            </button>

            <form :action="itemToDelete ? `{{ $baseUrl }}` + '/' + itemToDelete.id : '#'" method="POST"
                class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none">
                    {{ __('Obriši') }}
                </button>
            </form>
        </div>
    </div>
</div>
