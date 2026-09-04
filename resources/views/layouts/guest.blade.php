<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Helpdesk') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#2d2d2d] antialiased bg-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-8">
                <h1 class="text-[24px] font-bold tracking-tight uppercase">Helpdesk</h1>
            </div>
            <div class="w-full sm:max-w-md px-10 py-12 bg-white border border-[#e5e5e5] rounded-none shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
