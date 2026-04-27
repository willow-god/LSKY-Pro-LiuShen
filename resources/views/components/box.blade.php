<div class="rounded-2xl w-full overflow-hidden transition-colors duration-300" style="background: var(--card-bg); border: 1px solid var(--border-color); box-shadow: var(--card-shadow);">
    <div class="px-5 py-3.5 flex items-center gap-2.5" style="border-bottom: 1px solid var(--border-color); background: var(--card-header-bg);">
        <span class="w-1 h-4 rounded-full flex-shrink-0" style="background: linear-gradient(180deg, #10b981, #0d9488);"></span>
        <span class="text-slate-700 dark:text-slate-100 font-semibold text-sm">{{ $title }}</span>
    </div>
    <div class="w-full" style="background: var(--card-bg);">
        {{ $content }}
    </div>
</div>
