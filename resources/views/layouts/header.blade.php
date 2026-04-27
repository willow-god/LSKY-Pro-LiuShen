<header class="transition-all duration-300 w-full h-14 fixed top-0 z-[9] flex justify-center border-b border-[var(--header-border)] shadow-[var(--header-shadow)]" style="background: var(--header-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
    <x-container class="w-full px-6 flex justify-between items-center">
        <div class="flex justify-start items-center gap-3">
            <a href="javascript:void(0)" @click="$store.sidebar.toggle()" class="w-8 h-8 rounded-lg sm:hidden flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-all duration-200">
                <i class="fas fa-bars text-sm"></i>
            </a>
            <h1 class="text-sm font-semibold text-slate-700 dark:text-slate-100 truncate max-w-[200px]" id="header-title">@yield('title', \App\Utils::config(\App\Enums\ConfigKey::AppName))</h1>
        </div>
        <div class="flex justify-end items-center gap-3">
            @includeWhen($_is_notice, 'layouts.notice')
            @includeWhen($_group->strategies->isNotEmpty(), 'layouts.strategies')
            @include('layouts.user-nav')
        </div>
    </x-container>
</header>
