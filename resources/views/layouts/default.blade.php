<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seoData['title'] ?? config('app.name', 'Laravel') }}</title>

    @if(!empty($seoData['description']))
        <meta name="description" content="{{ $seoData['description'] }}">
    @endif
    @if(!empty($seoData['keywords']))
        <meta name="keywords" content="{{ $seoData['keywords'] }}">
    @endif

    @stack('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Try to use Vite assets if available, otherwise fallback to CDN --}}
    @hasSection('vite-disabled')
        <script src="https://cdn.tailwindcss.com"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Include Flux appearance if available --}}
    @if(function_exists('fluxAppearance'))
        @fluxAppearance
    @endif

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Simple Header -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="text-xl font-semibold text-gray-800 dark:text-white">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8 flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Simple Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Powered by FiltCMS.
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
