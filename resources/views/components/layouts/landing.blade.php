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
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <!-- Open Graph -->
    <meta property="og:site_name" content="Toota Art">
    <meta property="og:title" content="{{ $title ?? 'Toota Art' }}">
    <meta property="og:description" content="{{ __('about.description') }}">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta property="og:type" content="website">

    <title>{{ $title ?? 'Toota Art' }}</title>
    @vite(entrypoints: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/analytics.js',])
    @livewireStyles
</head>

<body class="bg-white text-gray-900">
    <x-landing.nav />
    <main>
        {{ $slot }}
    </main>
    <x-landing.footer />
    <x-notification />
    @fluxScripts
    @livewireScripts
</body>

</html>