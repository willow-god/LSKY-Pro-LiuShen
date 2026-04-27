<!-- Profile dropdown -->
<x-dropdown>
    <x-slot name="trigger">
        <button type="button" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-all duration-200 text-slate-700 dark:text-slate-200" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
            <img class="h-8 w-8 rounded-full ring-2 ring-emerald-100 dark:ring-emerald-500/20 object-cover" src="{{ Auth::user()->avatar }}" alt="">
            <span class="text-sm font-medium hidden sm:block">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down text-xs text-slate-400 dark:text-slate-500 hidden sm:block"></i>
        </button>
    </x-slot>

    <x-slot name="content">
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800/80">
                <p class="text-xs text-slate-500 dark:text-slate-400">登录身份</p>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-100 truncate">{{ Auth::user()->email }}</p>
            </div>
            <x-dropdown-link href="{{ route('images') }}"><i class="fas fa-images w-4 text-emerald-400"></i> 我的图片</x-dropdown-link>
            <x-dropdown-link href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt w-4 text-emerald-400"></i> 仪表盘</x-dropdown-link>
            <x-dropdown-link href="{{ route('settings') }}"><i class="fas fa-cog w-4 text-emerald-400"></i> 个人设置</x-dropdown-link>
            <div class="border-t border-slate-100 dark:border-slate-800/80 mt-1 pt-1">
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10">
                    <i class="fas fa-sign-out-alt w-4"></i> {{ __('Log Out') }}
                </x-dropdown-link>
            </div>
        </form>
    </x-slot>
</x-dropdown>
