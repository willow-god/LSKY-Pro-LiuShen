<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-1.5 py-2 px-5 text-sm font-semibold rounded-lg text-white transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500/50']) }} style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 2px 10px rgba(16,185,129,0.3);">
    {{ $slot }}
</button>
