<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Background Video & Overlay -->
        <video autoplay loop muted playsinline class="fixed top-0 left-0 w-full h-full object-cover -z-20">
            <source src="{{ asset('videos/background-video.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="fixed top-0 left-0 w-full h-full bg-black opacity-50 -z-10"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate>
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-24 h-24">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/90 dark:bg-gray-800/90 shadow-md overflow-hidden sm:rounded-lg backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
