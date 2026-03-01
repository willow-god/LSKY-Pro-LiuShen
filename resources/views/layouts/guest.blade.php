<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="{{ \App\Utils::config(\App\Enums\ConfigKey::SiteKeywords) }}"/>
        <meta name="description" content="{{ \App\Utils::config(\App\Enums\ConfigKey::SiteDescription) }}"/>

        <title>{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
        @stack('styles')

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/common.css') }}?t=20260302">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?t=20260302">
        <style>
            body { font-family: 'Inter', 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', 'Noto Sans CJK SC', 'Source Han Sans SC', 'WenQuanYi Micro Hei', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen text-slate-900" style="background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 50%, #f0f9ff 100%);">
            {{ $slot }}
        </div>
    </body>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?t=20260301"></script>
    @if(file_exists(public_path('js/custom.js')))
        <script src="{{ asset('js/custom.js') }}"></script>
    @endif
    @stack('scripts')
</html>
