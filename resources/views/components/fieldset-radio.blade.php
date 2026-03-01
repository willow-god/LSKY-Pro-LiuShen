<div class="flex items-center">
    <input type="radio" {{ $attributes->merge(['id' => $id, 'name' => $name, 'class' => 'focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-slate-300 cursor-pointer', 'value' => $value ?? 0]) }}>
    <label for="{{ $id }}" class="ml-2 text-sm font-medium text-slate-700 cursor-pointer select-none">{{ $slot }}</label>
</div>
