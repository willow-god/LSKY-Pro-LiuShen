<nav class="transition-all duration-300 -left-[600px] sm:left-0 w-3/4 sm:w-64 h-screen fixed z-10 flex flex-col border-r shadow-[var(--sidebar-shadow)]" style="background: var(--sidebar-bg); border-color: var(--sidebar-border);" :class="{
    '-left-[600px]': ! $store.sidebar.open,
    'left-0': $store.sidebar.open
}">
    {{-- Brand / Logo --}}
    <div class="px-5 h-14 flex justify-between items-center flex-shrink-0 border-b" style="background: var(--sidebar-surface); border-color: var(--sidebar-border);">
        <a href="/" class="flex items-center gap-2 truncate">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, #10b981, #0d9488);">
                <i class="fas fa-feather-alt text-white text-sm"></i>
            </span>
            <span class="font-bold text-base truncate gradient-text">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
        </a>
        <a href="javascript:void(0)" class="sm:hidden flex w-8 h-8 rounded-lg items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" @click="$store.sidebar.open = false">
            <i class="fas fa-times text-sm"></i>
        </a>
    </div>

    {{-- Navigation --}}
    <div class="flex flex-col justify-between flex-1 p-3 overflow-y-auto overscroll-contain scrollbar-none">
        <div class="space-y-1">
            {{-- 概览 --}}
            <div class="mb-3">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <x-slot name="icon"><i class="fas fa-tachometer-alt"></i></x-slot>
                    <x-slot name="name">仪表盘</x-slot>
                </x-nav-link>
            </div>

            {{-- 我的 --}}
            <p class="text-xs font-semibold uppercase tracking-widest px-3 mb-2 text-[var(--text-muted)]">我的</p>
            <x-nav-link :href="route('upload')" :active="request()->routeIs('upload')">
                <x-slot name="icon"><i class="fas fa-cloud-upload-alt"></i></x-slot>
                <x-slot name="name">上传图片</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('images')" :active="request()->routeIs('images')">
                <x-slot name="icon"><i class="fas fa-images"></i></x-slot>
                <x-slot name="name">我的图片</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('settings')" :active="request()->routeIs('settings')">
                <x-slot name="icon"><i class="fas fa-user-cog"></i></x-slot>
                <x-slot name="name">个人设置</x-slot>
            </x-nav-link>

            {{-- 公共 --}}
            @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableGallery) || \App\Utils::config(\App\Enums\ConfigKey::IsEnableApi))
            <p class="text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-4 text-[var(--text-muted)]">公共</p>
            @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableGallery))
            <x-nav-link :href="route('gallery')" :active="request()->routeIs('gallery')">
                <x-slot name="icon"><i class="fas fa-chalkboard"></i></x-slot>
                <x-slot name="name">画廊</x-slot>
            </x-nav-link>
            @endif
            @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableApi))
            <x-nav-link :href="route('api')" :active="request()->routeIs('api')">
                <x-slot name="icon"><i class="fas fa-link"></i></x-slot>
                <x-slot name="name">接口文档</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('api.tokens')" :active="request()->routeIs('api.tokens')">
                <x-slot name="icon"><i class="fas fa-key"></i></x-slot>
                <x-slot name="name">密钥管理</x-slot>
            </x-nav-link>
            @endif
            @endif

            {{-- 系统管理 --}}
            @if(Auth::user()->is_adminer)
            <p class="text-xs font-semibold uppercase tracking-widest px-3 mb-2 mt-4 text-[var(--text-muted)]">系统管理</p>
            <x-nav-link :href="route('admin.console')" :active="request()->is('admin/console*')">
                <x-slot name="icon"><i class="fas fa-terminal"></i></x-slot>
                <x-slot name="name">控制台</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('admin.groups')" :active="request()->is('admin/groups*')">
                <x-slot name="icon"><i class="fas fa-layer-group"></i></x-slot>
                <x-slot name="name">角色组</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('admin.users')" :active="request()->is('admin/users*')">
                <x-slot name="icon"><i class="fas fa-users-cog"></i></x-slot>
                <x-slot name="name">用户管理</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('admin.images')" :active="request()->is('admin/images*')">
                <x-slot name="icon"><i class="fas fa-photo-video"></i></x-slot>
                <x-slot name="name">图片管理</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('admin.strategies')" :active="request()->is('admin/strategies*')">
                <x-slot name="icon"><i class="fas fa-server"></i></x-slot>
                <x-slot name="name">储存策略</x-slot>
            </x-nav-link>
            <x-nav-link :href="route('admin.settings')" :active="request()->is('admin/settings*')">
                <x-slot name="icon"><i class="fas fa-cog"></i></x-slot>
                <x-slot name="name">系统设置</x-slot>
            </x-nav-link>
            @endif
        </div>

        {{-- 容量进度 --}}
        <div id="capacity-progress" class="mt-6 mx-1 p-4 rounded-xl border" style="background: var(--capacity-bg); border-color: var(--capacity-border);">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-medium text-[var(--text-secondary)]">容量使用</p>
                <i class="fas fa-database text-xs text-emerald-400"></i>
            </div>
            <progress class="w-full h-1.5 rounded-full" value="{{ Auth::user()->use_capacity }}" max="{{ Auth::user()->capacity }}"></progress>
            <div class="flex justify-between mt-2">
                <p class="text-xs truncate text-emerald-600 dark:text-emerald-400">
                    <span class="used">{{ \App\Utils::formatSize(Auth::user()->use_capacity * 1024) }}</span>
                </p>
                <p class="text-xs text-[var(--text-muted)]">
                    <span class="total">{{ \App\Utils::formatSize(Auth::user()->capacity * 1024) }}</span>
                </p>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        let $progress = $('#capacity-progress progress');
        let value = $progress.attr('value') / $progress.attr('max') * 100;
        let str = 'green';
        if (value > 90) {
            str = 'red';
        } else if (value > 70) {
            str = 'orange';
        } else if (value > 60) {
            str = 'yellow';
        } else if (value > 40) {
            str = 'yellowgreen';
        }
        $progress.addClass(str)
    </script>
@endpush
