<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Science+Gothic:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700"
        rel="stylesheet">

        <title>{{ $title ?? 'Toota Art' }}</title>
    @vite(entrypoints: ['resources/css/app.css', 'resources/js/app.js','resources/js/analytics.js',])
    @livewireStyles
</head>

<body class="bg-white text-gray-900">
    <x-landing.nav />
    <main>
        {{ $slot }}
    </main>
    <x-landing.footer />
    @livewireScripts
</body>

</html>