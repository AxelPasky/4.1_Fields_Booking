<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen flex flex-col items-center justify-center bg-gray-100 text-gray-800">
            
            {{-- Main Content --}}
            <div class="text-center p-6 max-w-2xl mx-auto">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-28 h-28">
                    </a>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl md:text-5xl font-bold">
                    Book Your Field, Simply.
                </h1>

                <!-- Sub-headline -->
                <p class="mt-4 text-lg text-gray-600">
                    The easiest way to manage and book sports fields. Check availability in real-time and get instant confirmations.
                </p>

                <!-- Call to Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-brand-green-600 hover:bg-brand-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-500">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-green-500">
                        Register
                    </a>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="absolute bottom-0 left-0 right-0 p-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </footer>
        </div>
    </body>
</html>
