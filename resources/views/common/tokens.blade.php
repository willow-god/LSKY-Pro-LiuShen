@section('title', 'API 密钥管理')

<x-app-layout>
    <div class="my-6 md:my-8 max-w-5xl">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                    <i class="fas fa-key text-sm" style="color: #059669;"></i>
                </div>
                <h2 class="font-bold text-lg text-slate-800 truncate">API 密钥管理</h2>
            </div>
            <x-button type="button" @click="$store.modal.open('create-token-modal')">
                <i class="fas fa-plus mr-1.5"></i>新建密钥
            </x-button>
        </div>

        {{-- Token 列表 --}}
        <div class="rounded-2xl overflow-hidden" style="background: var(--panel-bg-strong); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow);">
            @if($tokens->isEmpty())
                <x-no-data message="暂无 API 密钥" />
            @else
                <x-table :columns="['名称', '最后使用时间', '创建时间', '操作']">
                    @foreach($tokens as $token)
                        <tr class="align-top">
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-slate-700">
                                <span id="token-name-display-{{ $token->id }}">{{ $token->name }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $token->last_used_at ? $token->last_used_at->format('Y-m-d H:i:s') : '从未使用' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $token->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-3 whitespace-nowrap">
                                    <button type="button"
                                        class="token-view-btn text-emerald-600 hover:text-emerald-700 text-sm font-medium transition-colors shrink-0"
                                        data-token-id="{{ $token->id }}"
                                        data-token-name="{{ $token->name }}">
                                        <i class="fas fa-eye mr-1"></i>查看
                                    </button>
                                    <button type="button"
                                        class="token-delete-btn text-red-500 hover:text-red-700 text-sm font-medium transition-colors shrink-0"
                                        data-token-id="{{ $token->id }}"
                                        data-token-name="{{ $token->name }}">
                                        <i class="fas fa-trash-alt mr-1"></i>删除
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </div>

        <div class="mt-4 text-sm text-slate-500 space-y-1">
            <p><i class="fas fa-info-circle mr-1"></i>提示：创建密钥时默认授予全部权限，你可以按需取消勾选。</p>
            <p><i class="fas fa-info-circle mr-1"></i>提示：删除密钥后，使用该密钥的 API 请求将立即失效。</p>
        </div>
    </div>

    {{-- 创建 Token 弹窗 --}}
    <x-modal id="create-token-modal">
        <div class="w-full">
            <div id="create-token-panel-form">
                <h3 class="text-lg font-bold text-slate-800 mb-1">新建 API 密钥</h3>
                <p class="text-sm text-slate-500 mb-6">
                    <i class="fas fa-shield-alt mr-1 text-emerald-500"></i>为了安全，请再次输入密码验证身份
                </p>

                <form id="create-token-form" onsubmit="return false;">
                    @csrf
                    <input type="text" value="{{ $currentUserEmail }}" autocomplete="username" tabindex="-1" class="hidden" aria-hidden="true">
                    <div class="mb-4">
                        <label for="token-name" class="block text-sm font-medium text-slate-700 mb-1.5">
                            密钥名称 <span class="text-slate-400 font-normal">（可选，默认使用邮箱）</span>
                        </label>
                        <x-input type="text" id="token-name" name="name" placeholder="例如：PicGo、uPic、开发测试..." maxlength="50" autocomplete="username" />
                    </div>

                    <div class="mb-4">
                        <label for="token-password" class="block text-sm font-medium text-slate-700 mb-1.5">
                            <i class="fas fa-lock mr-1 text-slate-400"></i>当前密码 <span class="text-red-500">*</span>
                        </label>
                        <x-input type="password" id="token-password" name="password" placeholder="请再次输入密码验证身份" autocomplete="current-password" required />
                    </div>

                    <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-slate-700">授权范围</p>
                            <p class="text-xs text-slate-500 mt-1">默认全部勾选，创建后只能查看，不能修改。</p>
                        </div>

                        <label class="inline-flex items-start gap-2.5 text-sm text-slate-700 cursor-pointer mb-4">
                            <input type="checkbox" id="toggle-all-abilities" class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" checked>
                            <span>
                                <span class="font-semibold text-slate-700">权限组</span>
                                <span class="block text-xs text-slate-400 mt-1">全选或取消全部权限</span>
                            </span>
                        </label>

                        <div class="space-y-4">
                            @foreach($tokenAbilityGroups as $group)
                                <div class="rounded-lg bg-white border border-slate-200 p-4">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <label class="inline-flex items-start gap-2.5 text-sm text-slate-700 cursor-pointer">
                                            <input type="checkbox" class="ability-group-toggle mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" data-group-key="{{ $group['key'] }}" checked>
                                            <span>
                                                <span class="font-semibold text-slate-700">{{ $group['label'] }}</span>
                                                <span class="block text-xs text-slate-400 mt-1">{{ count($group['abilities']) }} 项权限</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach($group['abilities'] as $abilityKey => $abilityLabel)
                                            <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 cursor-pointer transition-colors hover:border-emerald-200 hover:bg-emerald-50/60">
                                                <input type="checkbox" name="abilities[]" value="{{ $abilityKey }}" data-group-key="{{ $group['key'] }}" class="ability-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" checked>
                                                <span>{{ $abilityLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6">
                        <button type="button" id="btn-close-create-token" onclick="closeCreateTokenModal()" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            关闭
                        </button>
                        <x-button type="button" id="btn-create-token" onclick="createToken()">
                            <i class="fas fa-check mr-1.5"></i>确认创建
                        </x-button>
                    </div>
                </form>
            </div>

            <div id="create-token-panel-result" class="hidden">
                <h3 class="text-lg font-bold text-slate-800 mb-1">API 密钥创建成功</h3>
                <p class="text-sm text-slate-500 mb-6">请立即复制保存此密钥，关闭后将无法再次查看完整明文。</p>

                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-xs text-emerald-700 mb-1">密钥名称</p>
                    <p id="token-result-name" class="text-sm font-semibold text-emerald-900 break-all"></p>
                </div>

                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <p class="text-sm font-medium text-emerald-700">密钥内容</p>
                        <button type="button" onclick="copyToken()" class="inline-flex items-center gap-1 rounded-lg bg-white/80 px-3 py-1.5 text-sm font-medium text-emerald-700 hover:bg-white transition-colors shrink-0 max-w-[120px] sm:max-w-none">
                            <i class="fas fa-copy shrink-0"></i>
                            <span class="truncate">复制</span>
                        </button>
                    </div>
                    <code id="token-value" class="block w-full rounded-lg border border-emerald-200 bg-white px-3 py-2 text-sm text-slate-700 overflow-hidden text-ellipsis whitespace-nowrap"></code>
                </div>

                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-xs font-semibold text-emerald-700 mb-3">已授予权限</p>
                    <div id="token-abilities-result" class="space-y-2"></div>
                </div>

                <p class="text-xs text-emerald-600 mb-6"><i class="fas fa-sync-alt mr-1"></i>关闭后会自动刷新列表，以显示最新创建的密钥。</p>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeCreateTokenModalAndReload()" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        关闭并刷新列表
                    </button>
                </div>
            </div>
        </div>
    </x-modal>

    {{-- 查看 Token 弹窗 --}}
    <x-modal id="view-token-modal">
        <div class="w-full">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1">查看密钥</h3>
                <p class="text-sm text-slate-500">可在此处重命名密钥，并查看当前授权范围</p>
            </div>

            <input type="hidden" id="view-token-id">

            <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                <p class="text-xs text-slate-500 mb-1">密钥名称</p>
                <div class="flex flex-wrap items-start gap-x-3 gap-y-2">
                    <p id="view-token-name-text" class="text-sm font-semibold text-slate-700 break-all min-w-0 flex-1"></p>
                    <button type="button" id="btn-start-rename-token" onclick="beginRenameFromViewModal()" class="inline-flex items-center gap-1 text-sm font-medium text-sky-600 hover:text-sky-700 transition-colors shrink-0">
                        <i class="fas fa-edit"></i>重命名
                    </button>
                </div>

                <div id="view-token-name-editor" class="hidden mt-3 border-t border-slate-200 p-3">
                    <label for="view-token-name-input" class="block text-sm font-medium text-slate-700 mb-1.5">
                        新名称 <span class="text-red-500">*</span>
                    </label>
                    <x-input type="text" id="view-token-name-input" name="view_token_name" placeholder="请输入密钥名称" maxlength="50" required autocomplete="off" />
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-4">
                        <button type="button" onclick="cancelRenameFromViewModal()" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            取消
                        </button>
                        <x-button type="button" id="btn-save-view-token-name" onclick="saveTokenNameFromViewModal()">
                            <i class="fas fa-save mr-1.5"></i>保存名称
                        </x-button>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-sm font-semibold text-slate-700 mb-3">已授予权限</p>
                <div id="view-token-abilities" class="space-y-3"></div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" @click="$store.modal.close('view-token-modal')" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors w-full sm:w-auto">
                    关闭
                </button>
            </div>
        </div>
    </x-modal>

    {{-- 删除 Token 弹窗 --}}
    <x-modal id="delete-token-modal">
        <div class="w-full">
            <h3 class="text-lg font-bold text-slate-800 mb-1">删除 API 密钥</h3>
            <p class="text-sm text-slate-500 mb-6">
                <i class="fas fa-shield-alt mr-1 text-emerald-500"></i>为了安全，请再次输入密码验证身份
            </p>

            <form id="delete-token-form" onsubmit="return false;">
                @csrf
                <input type="text" value="{{ $currentUserEmail }}" autocomplete="username" tabindex="-1" class="hidden" aria-hidden="true">
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100">
                    <p class="text-sm text-red-700 break-all">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        确定要删除密钥「<span id="delete-token-name" class="font-semibold"></span>」吗？
                    </p>
                    <p class="text-xs text-red-500 mt-1">删除后使用该密钥的 API 请求将立即失效。</p>
                </div>

                <input type="hidden" id="delete-token-id" />

                <div class="mb-4">
                    <label for="delete-password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        <i class="fas fa-lock mr-1 text-slate-400"></i>当前密码 <span class="text-red-500">*</span>
                    </label>
                    <x-input type="password" id="delete-password" name="password" placeholder="请再次输入密码验证身份" autocomplete="current-password" required />
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6">
                    <button type="button" @click="$store.modal.close('delete-token-modal')" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        取消
                    </button>
                    <button type="button" id="btn-delete-token" onclick="confirmDeleteToken()" class="inline-flex items-center justify-center gap-1.5 py-2 px-5 text-sm font-semibold rounded-lg text-white transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-red-500/50" style="background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 2px 10px rgba(239,68,68,0.3);">
                        <i class="fas fa-trash-alt mr-1.5"></i>确认删除
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    @push('scripts')
        <script>
            const tokenAbilityGroups = @json($tokenAbilityGroups);
            const tokenAbilitiesMap = @json($tokenAbilitiesMap);

            function getSelectedAbilities() {
                return Array.from(document.querySelectorAll('.ability-checkbox:checked')).map(item => item.value);
            }

            function renderAbilityGroups(container, abilityGroups, emptyText = '暂无权限') {
                container.innerHTML = '';

                if (!abilityGroups.length) {
                    const empty = document.createElement('p');
                    empty.className = 'text-sm text-slate-500';
                    empty.textContent = emptyText;
                    container.appendChild(empty);
                    return;
                }

                abilityGroups.forEach(group => {
                    const block = document.createElement('div');
                    block.className = 'rounded-lg border border-emerald-100 bg-white/80 p-3';

                    const title = document.createElement('p');
                    title.className = 'text-xs font-semibold text-emerald-700 mb-2';
                    title.textContent = group.label;
                    block.appendChild(title);

                    const list = document.createElement('div');
                    list.className = 'flex flex-wrap gap-2';

                    group.abilities.forEach(ability => {
                        const badge = document.createElement('span');
                        badge.className = 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100';
                        badge.textContent = ability.label;
                        list.appendChild(badge);
                    });

                    block.appendChild(list);
                    container.appendChild(block);
                });
            }

            function syncTokenName(id, name) {
                const displayEl = document.getElementById('token-name-display-' + id);
                if (displayEl) {
                    displayEl.textContent = name;
                }

                document.querySelectorAll(`[data-token-id="${id}"]`).forEach(button => {
                    if ('tokenName' in button.dataset) {
                        button.dataset.tokenName = name;
                    }
                });
            }

            function setViewRenameMode(editing) {
                document.getElementById('btn-start-rename-token').classList.toggle('hidden', editing);
                document.getElementById('view-token-name-editor').classList.toggle('hidden', !editing);
            }

            function openViewModal(id, name) {
                document.getElementById('view-token-id').value = id;
                document.getElementById('view-token-name-text').textContent = name;
                document.getElementById('view-token-name-input').value = name;
                setViewRenameMode(false);
                renderAbilityGroups(document.getElementById('view-token-abilities'), tokenAbilitiesMap[String(id)] || []);
                Alpine.store('modal').open('view-token-modal');
            }

            function beginRenameFromViewModal() {
                setViewRenameMode(true);
                const input = document.getElementById('view-token-name-input');
                input.focus();
                input.select();
            }

            function cancelRenameFromViewModal() {
                document.getElementById('view-token-name-input').value = document.getElementById('view-token-name-text').textContent;
                setViewRenameMode(false);
            }

            function updateGroupToggleState(groupKey) {
                const checkboxes = document.querySelectorAll(`.ability-checkbox[data-group-key="${groupKey}"]`);
                const toggle = document.querySelector(`.ability-group-toggle[data-group-key="${groupKey}"]`);

                if (!toggle || !checkboxes.length) {
                    return;
                }

                toggle.checked = Array.from(checkboxes).every(item => item.checked);
            }

            function updateToggleAllState() {
                const checkboxes = document.querySelectorAll('.ability-checkbox');
                const checked = document.querySelectorAll('.ability-checkbox:checked');
                const toggleAll = document.getElementById('toggle-all-abilities');

                if (!toggleAll) {
                    return;
                }

                toggleAll.checked = checkboxes.length === checked.length;
            }

            function closeCreateTokenModal() {
                Alpine.store('modal').close('create-token-modal');
            }

            function closeCreateTokenModalAndReload() {
                Alpine.store('modal').close('create-token-modal');
                setTimeout(() => location.reload(), 600);
            }

            function resetCreateTokenModal() {
                document.getElementById('create-token-form').reset();
                document.getElementById('create-token-panel-form').classList.remove('hidden');
                document.getElementById('create-token-panel-result').classList.add('hidden');
                document.getElementById('token-abilities-result').innerHTML = '';
                document.getElementById('token-value').textContent = '';
                document.getElementById('token-result-name').textContent = '';
                document.getElementById('token-name').disabled = false;
                document.getElementById('token-password').disabled = false;
                document.querySelectorAll('.ability-checkbox, .ability-group-toggle').forEach(item => {
                    item.disabled = false;
                    item.checked = true;
                });
                document.getElementById('toggle-all-abilities').disabled = false;
                document.getElementById('btn-close-create-token').textContent = '关闭';
                document.getElementById('btn-create-token').classList.remove('hidden');
                updateToggleAllState();
                tokenAbilityGroups.forEach(group => updateGroupToggleState(group.key));
            }

            function createToken() {
                const password = document.getElementById('token-password').value.trim();
                if (!password) {
                    toastr.warning('请输入密码以验证身份');
                    return;
                }

                const abilities = getSelectedAbilities();
                if (!abilities.length) {
                    toastr.warning('请至少选择一项权限');
                    return;
                }

                const btn = document.getElementById('btn-create-token');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>创建中...';

                const name = document.getElementById('token-name').value.trim();

                axios.post('{{ route("api.tokens.create") }}', {
                    name: name || null,
                    password: password,
                    abilities: abilities,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }).then(response => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-1.5"></i>确认创建';

                    if (response.data.status) {
                        document.getElementById('token-result-name').textContent = name || '{{ $currentUserEmail }}';
                        document.getElementById('token-value').textContent = response.data.data.token;
                        renderAbilityGroups(document.getElementById('token-abilities-result'), response.data.data.ability_groups || []);
                        document.getElementById('create-token-panel-form').classList.add('hidden');
                        document.getElementById('create-token-panel-result').classList.remove('hidden');
                        toastr.success('密钥创建成功');
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-1.5"></i>确认创建';
                    toastr.error('请求失败，请稍后重试');
                });
            }

            function copyToken() {
                const token = document.getElementById('token-value').textContent;
                navigator.clipboard.writeText(token).then(() => {
                    toastr.success('已复制到剪贴板');
                }).catch(() => {
                    const textarea = document.createElement('textarea');
                    textarea.value = token;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    toastr.success('已复制到剪贴板');
                });
            }

            function saveTokenNameFromViewModal() {
                const id = document.getElementById('view-token-id').value;
                const name = document.getElementById('view-token-name-input').value.trim();

                if (!name) {
                    toastr.warning('请输入密钥名称');
                    return;
                }

                const btn = document.getElementById('btn-save-view-token-name');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>保存中...';

                axios.put('{{ url("api/tokens") }}/' + id, {
                    name: name,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }).then(response => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-1.5"></i>保存名称';

                    if (response.data.status) {
                        document.getElementById('view-token-name-text').textContent = name;
                        document.getElementById('view-token-name-input').value = name;
                        syncTokenName(id, name);
                        setViewRenameMode(false);
                        toastr.success('修改成功');
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-1.5"></i>保存名称';
                    toastr.error('修改失败，请稍后重试');
                });
            }

            function openDeleteModal(id, name) {
                document.getElementById('delete-token-id').value = id;
                document.getElementById('delete-token-name').textContent = name;
                document.getElementById('delete-password').value = '';
                Alpine.store('modal').open('delete-token-modal');
            }

            function confirmDeleteToken() {
                const id = document.getElementById('delete-token-id').value;
                const password = document.getElementById('delete-password').value.trim();

                if (!password) {
                    toastr.warning('请输入密码以验证身份');
                    return;
                }

                const btn = document.getElementById('btn-delete-token');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>删除中...';

                axios.delete('{{ url("api/tokens") }}/' + id, {
                    data: {
                        password: password,
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash-alt mr-1.5"></i>确认删除';

                    if (response.data.status) {
                        Alpine.store('modal').close('delete-token-modal');
                        toastr.success('删除成功');
                        setTimeout(() => location.reload(), 600);
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash-alt mr-1.5"></i>确认删除';
                    toastr.error('删除失败，请稍后重试');
                });
            }

            document.addEventListener('click', (event) => {
                const viewButton = event.target.closest('.token-view-btn');
                if (viewButton) {
                    openViewModal(viewButton.dataset.tokenId, viewButton.dataset.tokenName || '');
                    return;
                }

                const deleteButton = event.target.closest('.token-delete-btn');
                if (deleteButton) {
                    openDeleteModal(deleteButton.dataset.tokenId, deleteButton.dataset.tokenName || '');
                    return;
                }

                const groupToggle = event.target.closest('.ability-group-toggle');
                if (groupToggle) {
                    document.querySelectorAll(`.ability-checkbox[data-group-key="${groupToggle.dataset.groupKey}"]`).forEach(item => {
                        item.checked = groupToggle.checked;
                    });
                    updateToggleAllState();
                    return;
                }

                const toggleAll = event.target.closest('#toggle-all-abilities');
                if (toggleAll) {
                    document.querySelectorAll('.ability-checkbox, .ability-group-toggle').forEach(item => {
                        item.checked = toggleAll.checked;
                    });
                    return;
                }
            });

            document.addEventListener('change', (event) => {
                const checkbox = event.target.closest('.ability-checkbox, #toggle-all-abilities');
                if (!checkbox) {
                    return;
                }

                if (checkbox.id === 'toggle-all-abilities') {
                    tokenAbilityGroups.forEach(group => updateGroupToggleState(group.key));
                    updateToggleAllState();
                    return;
                }

                updateGroupToggleState(checkbox.dataset.groupKey);
                updateToggleAllState();
            });

            document.addEventListener('alpine:initialized', () => {
                const createModal = document.getElementById('create-token-modal');
                if (createModal) {
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style' && createModal.style.display === 'none') {
                                setTimeout(() => {
                                    resetCreateTokenModal();
                                }, 300);
                            }
                        });
                    });
                    observer.observe(createModal, { attributes: true });
                }

                tokenAbilityGroups.forEach(group => updateGroupToggleState(group.key));
                updateToggleAllState();
            });
        </script>
    @endpush
</x-app-layout>
