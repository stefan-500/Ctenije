<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Prodavnica knjiga Čtenije" />
    <meta name="author" content="Stefan Vujović" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') . ' ' . __('Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Main Container -->
    <div class="flex bg-gray-100">
        <!-- Include the sidenav -->
        @can('access-admin')
            @include('components.sidenav')
        @endcan

        <!-- Main Content -->
        <div class="flex-grow ml-0">
            <!-- Include the top nav -->
            @include('components.nav')

            <!-- Page Content -->
            <div x-data="{ showDeleteModal: false, itemToDelete: null }" class="px-6 py-10">
                @yield('content')
                {{ $slot }}
            </div>

            <!-- Include the footer -->
            @include('components.footer')
        </div>
    </div>
</body>

</html>
