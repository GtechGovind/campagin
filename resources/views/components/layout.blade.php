<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'INTEGRACE - CAMPAIGN' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-900 h-screen flex flex-col relative">

<!-- Top Bar for Powered By -->
<header class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-center">
        <a
                href="https://qurkos.com"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center space-x-2 hover:opacity-80 transition-opacity duration-300">
            <img src="https://qurkos.com/assets/images/logo.svg" alt="Logo" class="h-6 w-auto">
            <span class="text-sm font-medium">Powered by Qurkos Pvt. Ltd.</span>
        </a>
    </div>
</header>

<!-- Main Content -->
<div class="flex-grow mt-12">
    {{ $slot }}
</div>

</body>
</html>
