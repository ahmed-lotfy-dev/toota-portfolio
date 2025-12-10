<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts here exactly as you want -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Science+Gothic:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <title>{{ $title ?? 'Toota Art Dashboard' }}</title>

    @vite(entrypoints: ['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-white text-gray-900 antialiased">
    <x-dashboard.dashboard-nav>
        {{ $slot }}
    </x-dashboard.dashboard-nav>

    @fluxScripts
    <x-ui.modal name="project-modal" title="Project Details">
        <x-slot name="body">
            <livewire:dashboard.forms.project-form-modal-content />
        </x-slot>
    </x-ui.modal>
    <x-ui.notification />
    @livewireScripts
</body>

</html>
