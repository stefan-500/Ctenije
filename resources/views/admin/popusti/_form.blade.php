@php
    $selectedType = old('type', $discountCode->type ?? 'percent');
    $formatMoney = fn($value) => $value !== null ? number_format($value / 100, 2, '.', '') : '';
    $valueInput = old(
        'value',
        $selectedType === 'fixed' && $discountCode->exists ? $formatMoney($discountCode->value) : $discountCode->value,
    );
    $minimumInput = old('minimum_order_total', $formatMoney($discountCode->minimum_order_total));
@endphp

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ __($error) }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">{{ __('Kod') }}</label>
            <input type="text" name="code" id="code" value="{{ old('code', $discountCode->code) }}"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm uppercase focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="USTEDI10" required>
            <p class="text-xs text-gray-500 mt-1">{{ __('Dozvoljena su velika slova, brojevi, _ i -.') }}</p>
            @error('code')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">{{ __('Tip popusta') }}</label>
            <select name="type" id="type"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('type') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="percent" {{ $selectedType === 'percent' ? 'selected' : '' }}>{{ __('Procenat') }}</option>
                <option value="fixed" {{ $selectedType === 'fixed' ? 'selected' : '' }}>{{ __('Fiksni iznos') }}</option>
            </select>
            @error('type')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="value" class="block text-sm font-medium text-gray-700">{{ __('Vrijednost') }}</label>
            <input type="text" name="value" id="value" value="{{ $valueInput }}"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('value') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="10" required>
            <p class="text-xs text-gray-500 mt-1">
                {{ __('Za procenat unesite 1-100. Za fiksni iznos unesite EUR, npr. 5.00.') }}
            </p>
            @error('value')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div class="flex items-center pt-6">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                {{ old('is_active', $discountCode->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">{{ __('Aktivan kod') }}</label>
        </div>

        <div>
            <label for="starts_at" class="block text-sm font-medium text-gray-700">{{ __('Početak važenja') }}</label>
            <input type="datetime-local" name="starts_at" id="starts_at"
                value="{{ old('starts_at', $discountCode->adminDateTimeInput('starts_at')) }}" step="60"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('starts_at') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <p class="text-xs text-gray-500 mt-1">{{ __('Unesite lokalno vrijeme za Europe/Belgrade, u 24h formatu.') }}</p>
            @error('starts_at')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="expires_at" class="block text-sm font-medium text-gray-700">{{ __('Istek važenja') }}</label>
            <input type="datetime-local" name="expires_at" id="expires_at"
                value="{{ old('expires_at', $discountCode->adminDateTimeInput('expires_at')) }}" step="60"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('expires_at') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <p class="text-xs text-gray-500 mt-1">{{ __('Unesite lokalno vrijeme za Europe/Belgrade, u 24h formatu.') }}</p>
            @error('expires_at')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="minimum_order_total" class="block text-sm font-medium text-gray-700">
                {{ __('Minimalni iznos porudžbine') }} <span class="text-xs uppercase">EUR</span>
            </label>
            <input type="text" name="minimum_order_total" id="minimum_order_total" value="{{ $minimumInput }}"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('minimum_order_total') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="30.00">
            @error('minimum_order_total')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="max_uses" class="block text-sm font-medium text-gray-700">{{ __('Globalni limit korišćenja') }}</label>
            <input type="number" name="max_uses" id="max_uses" min="1"
                value="{{ old('max_uses', $discountCode->max_uses) }}"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('max_uses') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('max_uses')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>

        <div>
            <label for="max_uses_per_email" class="block text-sm font-medium text-gray-700">{{ __('Limit po email adresi') }}</label>
            <input type="number" name="max_uses_per_email" id="max_uses_per_email" min="1"
                value="{{ old('max_uses_per_email', $discountCode->max_uses_per_email) }}"
                class="mt-1 block w-full px-3 py-2 bg-white border {{ $errors->has('max_uses_per_email') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            @error('max_uses_per_email')
                <p class="text-red-500 text-xs mt-1">{{ __($message) }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-end gap-3">
        <a href="{{ url('/admin/popusti/index') }}"
            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-center">
            {{ __('Otkaži') }}
        </a>
        <button type="submit"
            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            {{ $submitLabel }}
        </button>
    </div>
</form>
