@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm px-3 py-2.5 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed']) !!}>
