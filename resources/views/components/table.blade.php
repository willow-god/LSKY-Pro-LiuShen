<div class="flex flex-col">
    <div class="-my-2 sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block w-full sm:px-6 lg:px-8">
            <div class="overflow-x-auto rounded-xl w-full" style="background: var(--card-bg); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow);">
                <table class="min-w-full w-full">
                    <thead>
                    <tr style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-strong);">
                        @foreach($columns as $column)
                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-[var(--text-secondary)]">
                                {{ $column }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody style="background: var(--card-bg);" class="divide-y">
                    {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
