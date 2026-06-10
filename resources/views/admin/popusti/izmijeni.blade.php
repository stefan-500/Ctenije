<x-admin-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-8">
        <x-naslov-sekcije class="text-center mb-8">{{ __('Izmijeni kod za popust') }}</x-naslov-sekcije>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Iskorišćenja') }}</p>
                <p class="text-2xl font-bold">{{ $discountCode->uses_count }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Preostalo') }}</p>
                <p class="text-2xl font-bold">{{ $discountCode->remainingUses() ?? __('Neograničeno') }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Redemption zapisi') }}</p>
                <p class="text-2xl font-bold">{{ $discountCode->redemptions_count }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Važi od') }}</p>
                <p class="text-lg font-bold">{{ $discountCode->adminDateTimeDisplay('starts_at') ?? '-' }}</p>
            </div>
            <div class="bg-white border rounded p-4">
                <p class="text-sm text-gray-500">{{ __('Važi do') }}</p>
                <p class="text-lg font-bold">{{ $discountCode->adminDateTimeDisplay('expires_at') ?? '-' }}</p>
            </div>
        </div>

        @include('admin.popusti._form', [
            'action' => url('/admin/popusti/izmijeni/' . $discountCode->id),
            'method' => 'PUT',
            'submitLabel' => __('Ažuriraj kod'),
        ])
    </div>
</x-admin-layout>
