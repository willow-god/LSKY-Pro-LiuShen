<pre {{ $attributes->merge(['class' => 'my-2 rounded-md p-4 text-sm overflow-x-auto']) }} style="background: var(--code-bg); border: 1px solid var(--code-border); color: var(--code-text);">
{{ $slot ?? '' }}
</pre>
