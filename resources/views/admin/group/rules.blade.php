<x-modal id="rules-modal">
    <div id="modal-content">
        <div class="rounded-xl overflow-hidden" style="background: var(--card-bg); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow);">
            <table class="min-w-full w-full">
                <thead>
                <tr style="background: var(--card-header-bg); border-bottom: 1px solid var(--border-strong);">
                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-[var(--text-secondary)]">
                        规则
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-[var(--text-secondary)]">
                        说明
                    </th>
                </tr>
                </thead>
                <tbody style="background: var(--card-bg);" class="divide-y">
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{Y}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">年份(2022)</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{y}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">两位数年份(22)</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{m}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">月份(01)</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{d}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">当月的第几号(04)</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{timestamp}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">时间戳(秒)</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{uniqid}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">唯一字符串</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{md5}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">随机 md5 值</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{md5-16}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">16位随机 md5 值</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{str-random-16}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">16位随机字符串</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{str-random-10}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">10位随机字符串</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{filename}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">文件原始名称</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-sm text-[var(--text-secondary)]">{uid}</td>
                    <td class="px-3 py-2 text-sm text-[var(--text-primary)]">用户 ID，游客为 0</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-modal>

@push('scripts')
    <script>
        $('#rename-rules').click(function () {
            Alpine.store('modal').open('rules-modal')
        })
    </script>
@endpush
