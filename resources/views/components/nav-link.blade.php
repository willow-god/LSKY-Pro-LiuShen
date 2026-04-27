@props(['active'])

@php
$base = "sidebar-nav-link group flex items-center gap-3 px-3 h-10 w-full rounded-lg text-sm font-medium transition-all duration-200 ";
$activeClass = $base . (($active ?? false)
    ? "is-active text-emerald-700 shadow-sm dark:text-emerald-200"
    : "text-slate-600 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-100");
$iconBase = "w-5 text-center flex-shrink-0 transition-colors ";
$iconClass  = $iconBase . (($active ?? false) ? "text-emerald-500 dark:text-emerald-400" : "text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-300");
@endphp

<a {{ $attributes->merge(['class' => $activeClass]) }}>
    <span class="{{ $iconClass }}">{{ $icon }}</span>
    <span class="truncate">{{ $name }}</span>
    @if($active ?? false)
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
    @endif
</a>
