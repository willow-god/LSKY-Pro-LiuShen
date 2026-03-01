<fieldset class="py-1">
    <legend class="text-sm font-semibold text-slate-700">{{ $title }}</legend>
    @isset($faq)
        <p class="text-xs text-slate-400 mt-0.5">{!! $faq !!}</p>
    @endisset
    <div class="flex flex-wrap gap-3 mt-3">
        {{ $slot }}
    </div>
</fieldset>
