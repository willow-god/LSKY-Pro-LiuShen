<div class="flex flex-col">
    <div class="-my-2 sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block w-full sm:px-6 lg:px-8">
            <div class="overflow-x-auto rounded-xl bg-white w-full" style="border: 1px solid rgba(226,232,240,0.8); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(99,102,241,0.05);">
                <table class="min-w-full w-full">
                    <thead>
                    <tr style="background: linear-gradient(135deg, rgba(249,250,251,0.9), rgba(243,244,246,0.8)); border-bottom: 1px solid rgba(226,232,240,0.8);">
                        @foreach($columns as $column)
                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">
                                {{ $column }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100/80">
                    {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
