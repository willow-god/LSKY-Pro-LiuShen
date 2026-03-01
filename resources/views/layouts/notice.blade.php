@if($_is_notice)
    <button type="button" class="flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-lg text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all duration-200" id="open-notice" aria-expanded="false" aria-haspopup="true">
        <div class="h-8 w-8 rounded-full flex items-center justify-center" style="background: rgba(99,102,241,0.1);">
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
