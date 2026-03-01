@props(['active'])

@php
$base = "group flex items-center gap-3 px-3 h-10 w-full rounded-lg text-sm font-medium transition-all duration-200 ";
$activeClass = $base . (($active ?? false)
    ? "bg-emerald-50 text-emerald-700 shadow-sm"
    : "text-slate-600 hover:text-slate-900 hover:bg-slate-100");
$iconBase = "w-5 text-center flex-shrink-0 transition-colors ";
$iconClass  = $iconBase . (($active ?? false) ? "text-emerald-500" : "text-slate-400 group-hover:text-slate-600");
@endphp

<a {{ $attributes->merge(['class' => $activeClass]) }}>
    <span class="{{ $iconClass }}">{{ $icon }}</span>
    <span class="truncate">{{ $name }}</span>
    @if($active ?? false)
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
    @endif
</a>
