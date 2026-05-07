<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Helpdesk') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-[#555555]">
        <div class="flex min-h-screen">
            <!-- Sidebar Navigation -->
            <livewire:layout.navigation />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 bg-white">
                <!-- Page Heading (Optional) -->
                @if (isset($header))
                    <header class="bg-white border-b border-[#e5e5e5]">
                        <div class="max-w-7xl py-6 px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
