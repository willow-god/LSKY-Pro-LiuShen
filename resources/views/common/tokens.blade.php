@section('title', 'API 密钥管理')

<x-app-layout>
    <div class="my-6 md:my-8 max-w-5xl">
        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                    <i class="fas fa-key text-sm" style="color: #059669;"></i>
                </div>
                <h2 class="font-bold text-lg text-slate-800">API 密钥管理</h2>
            </div>
            <x-button type="button" @click="$store.modal.open('create-token-modal')">
                <i class="fas fa-plus mr-1.5"></i>新建密钥
            </x-button>
        </div>

        {{-- Token 列表 --}}
        <div class="rounded-2xl overflow-hidden" style="background: white; border: 1px solid rgba(226,232,240,0.8); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(16,185,129,0.05);">
            @if($tokens->isEmpty())
                <x-no-data message="暂无 API 密钥" />
            @else
                <x-table :columns="['名称', '最后使用时间', '创建时间', '操作']">
                    @foreach($tokens as $token)
                        <tr class="hover:bg-slate-50/60 transition-colors">
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
                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        class="token-edit-btn text-sky-500 hover:text-sky-700 text-sm font-medium transition-colors"
                                        data-token-id="{{ $token->id }}"
                                        data-token-name="{{ $token->name }}">
                                        <i class="fas fa-edit mr-1"></i>重命名
                                    </button>
                                    <button type="button"
                                        class="token-delete-btn text-red-500 hover:text-red-700 text-sm font-medium transition-colors"
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

        <div class="mt-4 text-sm text-slate-500">
            <p><i class="fas fa-info-circle mr-1"></i>提示：删除密钥后，使用该密钥的 API 请求将立即失效。</p>
        </div>
    </div>

    {{-- 创建 Token 弹窗 --}}
    <x-modal id="create-token-modal">
        <div class="w-full">
            <h3 class="text-lg font-bold text-slate-800 mb-1">新建 API 密钥</h3>
            <p class="text-sm text-slate-500 mb-6">
                <i class="fas fa-shield-alt mr-1 text-emerald-500"></i>为了安全，请再次输入密码验证身份
            </p>

            <form id="create-token-form" onsubmit="return false;">
                @csrf
                <div class="mb-4">
                    <label for="token-name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        密钥名称 <span class="text-slate-400 font-normal">（可选，默认使用邮箱）</span>
                    </label>
                    <x-input type="text" id="token-name" name="name" placeholder="例如：PicGo、uPic、开发测试..." maxlength="50" />
                </div>

                <div class="mb-4">
                    <label for="token-password" class="block text-sm font-medium text-slate-700 mb-1.5">
                        <i class="fas fa-lock mr-1 text-slate-400"></i>当前密码 <span class="text-red-500">*</span>
                    </label>
                    <x-input type="password" id="token-password" name="password" placeholder="请再次输入密码验证身份" autocomplete="current-password" required />
                </div>

                <div id="token-result" class="hidden mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
                    <p class="text-sm font-medium text-emerald-700 mb-2">密钥创建成功，请立即复制保存：</p>
                    <div class="flex items-center gap-2">
                        <code id="token-value" class="flex-1 block px-3 py-2 text-sm bg-white rounded border border-emerald-200 text-slate-700 break-all select-all"></code>
                        <button type="button" onclick="copyToken()" class="px-3 py-2 text-sm font-medium text-emerald-700 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition-colors whitespace-nowrap">
                            <i class="fas fa-copy mr-1"></i>复制
                        </button>
                    </div>
                    <p class="text-xs text-emerald-600 mt-2"><i class="fas fa-exclamation-triangle mr-1"></i>此密钥仅显示一次，关闭后将无法再次查看。</p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="$store.modal.close('create-token-modal')" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        关闭
                    </button>
                    <x-button type="button" id="btn-create-token" onclick="createToken()">
                        <i class="fas fa-check mr-1.5"></i>确认创建
                    </x-button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- 编辑 Token 名称弹窗 --}}
    <x-modal id="edit-token-modal">
        <div class="w-full">
            <h3 class="text-lg font-bold text-slate-800 mb-1">重命名密钥</h3>
            <p class="text-sm text-slate-500 mb-6">修改密钥的显示名称，便于识别和管理</p>

            <form id="edit-token-form" onsubmit="return false;">
                @csrf
                <input type="hidden" id="edit-token-id" />

                <div class="mb-4">
                    <label for="edit-token-name" class="block text-sm font-medium text-slate-700 mb-1.5">
                        密钥名称 <span class="text-red-500">*</span>
                    </label>
                    <x-input type="text" id="edit-token-name" name="name" placeholder="请输入密钥名称" maxlength="50" required />
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="$store.modal.close('edit-token-modal')" class="px-5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        取消
                    </button>
                    <x-button type="button" id="btn-edit-token" onclick="confirmEditToken()">
                        <i class="fas fa-save mr-1.5"></i>保存修改
                    </x-button>
                </div>
            </form>
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
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100">
                    <p class="text-sm text-red-700">
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

                <div class="flex justify-end gap-3 mt-6">
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
            function createToken() {
                const password = document.getElementById('token-password').value.trim();
                if (!password) {
                    toastr.warning('请输入密码以验证身份');
                    return;
                }

                const btn = document.getElementById('btn-create-token');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>创建中...';

                const name = document.getElementById('token-name').value.trim();

                axios.post('{{ route("api.tokens.create") }}', {
                    name: name || null,
                    password: password,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }).then(response => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check mr-1.5"></i>确认创建';

                    if (response.data.status) {
                        document.getElementById('token-value').textContent = response.data.data.token;
                        document.getElementById('token-result').classList.remove('hidden');
                        document.getElementById('token-name').disabled = true;
                        document.getElementById('token-password').disabled = true;
                        btn.classList.add('hidden');
                        toastr.success('密钥创建成功');
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(error => {
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

            function openEditModal(id, name) {
                document.getElementById('edit-token-id').value = id;
                document.getElementById('edit-token-name').value = name;
                Alpine.store('modal').open('edit-token-modal');
            }

            function confirmEditToken() {
                const id = document.getElementById('edit-token-id').value;
                const name = document.getElementById('edit-token-name').value.trim();

                if (!name) {
                    toastr.warning('请输入密钥名称');
                    return;
                }

                const btn = document.getElementById('btn-edit-token');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>保存中...';

                axios.put('{{ url("api/tokens") }}/' + id, {
                    name: name,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }).then(response => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-1.5"></i>保存修改';

                    if (response.data.status) {
                        Alpine.store('modal').close('edit-token-modal');
                        toastr.success('修改成功');
                        // 更新页面上显示的名称
                        const displayEl = document.getElementById('token-name-display-' + id);
                        if (displayEl) {
                            displayEl.textContent = name;
                        }
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save mr-1.5"></i>保存修改';
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
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toastr.warning(response.data.message);
                    }
                }).catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash-alt mr-1.5"></i>确认删除';
                    toastr.error('删除失败，请稍后重试');
                });
            }

            document.addEventListener('click', (event) => {
                const editButton = event.target.closest('.token-edit-btn');
                if (editButton) {
                    openEditModal(editButton.dataset.tokenId, editButton.dataset.tokenName || '');
                    return;
                }

                const deleteButton = event.target.closest('.token-delete-btn');
                if (deleteButton) {
                    openDeleteModal(deleteButton.dataset.tokenId, deleteButton.dataset.tokenName || '');
                }
            });

            // 弹窗关闭时重置状态
            document.addEventListener('alpine:initialized', () => {
                const createModal = document.getElementById('create-token-modal');
                if (createModal) {
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                if (createModal.style.display === 'none') {
                                    setTimeout(() => {
                                        document.getElementById('create-token-form').reset();
                                        document.getElementById('token-result').classList.add('hidden');
                                        document.getElementById('token-name').disabled = false;
                                        document.getElementById('token-password').disabled = false;
                                        document.getElementById('btn-create-token').classList.remove('hidden');
                                    }, 300);
                                }
                            }
                        });
                    });
                    observer.observe(createModal, { attributes: true });
                }
            });
        </script>
    @endpush
</x-app-layout>
