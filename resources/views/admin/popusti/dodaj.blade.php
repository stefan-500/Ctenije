<x-admin-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-8">
        <x-naslov-sekcije class="text-center mb-8">{{ __('Dodaj kod za popust') }}</x-naslov-sekcije>

        @include('admin.popusti._form', [
            'action' => url('/admin/popusti/dodaj'),
            'submitLabel' => __('Sačuvaj kod'),
        ])
    </div>
</x-admin-layout>
