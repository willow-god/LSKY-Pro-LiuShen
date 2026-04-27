@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'mt-1 block w-full rounded-lg border text-sm px-3 py-2.5 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed resize-none text-[var(--text-primary)]']) !!} style="background: var(--input-bg); border-color: var(--input-border);">{{ $slot ?? '' }}</textarea>
