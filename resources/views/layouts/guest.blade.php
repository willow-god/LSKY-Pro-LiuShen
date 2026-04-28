<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="relative min-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="{{ \App\Utils::config(\App\Enums\ConfigKey::SiteKeywords) }}"/>
        <meta name="description" content="{{ \App\Utils::config(\App\Enums\ConfigKey::SiteDescription) }}"/>

        <title>{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</title>

        <script>
            (() => {
                try {
                    const storedTheme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const theme = storedTheme === 'dark' || storedTheme === 'light'
                        ? storedTheme
                        : (prefersDark ? 'dark' : 'light');

                    document.documentElement.classList.toggle('dark', theme === 'dark');
                    document.documentElement.style.colorScheme = theme;
                } catch (error) {
                    document.documentElement.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
                }
            })();
        </script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://jsd.liiiu.cn/gh/willow-god/Sharding-fonts/GuanKiapTsingKhai/result.min.css">
        <!-- FontAwesome -->
        <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
        @stack('styles')

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/common.css') }}?t=20260302">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?t=20260302">
        <style>
            body { font-family: 'GuanKiapTsingKhai', 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', 'Noto Sans CJK SC', 'Source Han Sans SC', 'WenQuanYi Micro Hei', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-[var(--content-bg)] text-[var(--text-primary)] transition-colors duration-300">
        <div class="min-h-screen text-[var(--text-primary)] transition-colors duration-300 relative" x-data x-cloak style="background: var(--guest-bg);">
            {{ $slot }}

            <button
                type="button"
                class="theme-fab fixed right-4 floating-safe-bottom z-[19] inline-flex h-12 w-12 items-center justify-center rounded-full backdrop-blur-sm sm:right-6"
                @click="$store.theme.toggle()"
                :aria-label="$store.theme.isDark ? '切换到浅色模式' : '切换到深色模式'"
                :title="$store.theme.isDark ? '切换到浅色模式' : '切换到深色模式'"
            >
                <i class="fas text-base" :class="$store.theme.isDark ? 'fa-sun' : 'fa-moon'"></i>
            </button>
        </div>
    </body>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?t=20260301"></script>
    @if(file_exists(public_path('js/custom.js')))
        <script src="{{ asset('js/custom.js') }}"></script>
    @endif
    @stack('scripts')
</html>
