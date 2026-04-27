@if($_is_notice)
    <button type="button" class="topbar-pill flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-lg text-slate-600 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all duration-200" id="open-notice" aria-expanded="false" aria-haspopup="true">
        <div class="topbar-icon-shell h-8 w-8 rounded-full flex items-center justify-center bg-indigo-500/10 dark:bg-slate-700/80">
            <i class="fas fa-bell text-emerald-500 text-sm"></i>
        </div>
        <span class="text-sm font-medium hidden sm:block">公告</span>
    </button>
    @push('scripts')
        <script>
            $('#open-notice').click(function () {
                openNotice();
            });
        </script>
    @endpush
@endif
