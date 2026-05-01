@section('title', '设置')

@php
    $oauthError = isset($errors) ? $errors->first('oauth') : null;
    $pageError = null;

    if (isset($errors) && $errors->any()) {
        foreach ($errors->all() as $errorMessage) {
            if ($errorMessage !== $oauthError) {
                $pageError = $errorMessage;
                break;
            }
        }
    }

    $oauthEnabled = \App\Utils::config(\App\Enums\ConfigKey::OauthEnable);
    $oauthProviderName = \App\Utils::config(\App\Enums\ConfigKey::OauthProviderName) ?: 'OAuth 2.0';
    $hasOauthAccount = $oauthAccounts->isNotEmpty();
    $showOauthSection = $oauthEnabled || $hasOauthAccount || ! empty($oauthError);
@endphp

<x-app-layout>
    <div class="my-6 md:my-8 w-full">
        <div class="max-w-3xl">
            @if($pageError)
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $pageError }}
                </div>
            @endif

            @if(session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-5 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                    <i class="fas fa-user-cog text-sm" style="color: #059669;"></i>
                </div>
                <h2 class="font-bold text-lg text-slate-800">基础设置</h2>
            </div>

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="rounded-2xl overflow-hidden" style="background: var(--panel-bg-strong); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow);">
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-6 gap-5">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">邮箱</label>
                                <x-input type="text" id="email" autocomplete="email" value="{{ Auth::user()->email }}" disabled readonly/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">昵称</label>
                                <x-input type="text" name="name" id="name" autocomplete="name" value="{{ Auth::user()->name }}"/>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="default_strategy" class="block text-sm font-medium text-slate-700 mb-1.5">默认上传策略</label>
                                <x-select id="default_strategy" name="configs[default_strategy]" autocomplete="default-strategy">
                                    @if(Auth::user()->group)
                                        <option value="0">未选择</option>
                                        @foreach(Auth::user()->group->strategies as $strategy)
                                            <option value="{{ $strategy->id }}" @selected(Auth::user()->configs->get('default_strategy') == $strategy->id)>{{ $strategy->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="0">系统默认</option>
                                    @endif
                                </x-select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="default_album" class="block text-sm font-medium text-slate-700 mb-1.5">默认上传相册</label>
                                <x-select id="default_album" name="configs[default_album]" autocomplete="default-album">
                                    @if(Auth::user()->albums->isNotEmpty())
                                        <option value="0">未选择</option>
                                        @foreach(Auth::user()->albums as $album)
                                            <option value="{{ $album->id }}" @selected(Auth::user()->configs->get('default_album') == $album->id)>{{ $album->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="0">没有可用相册</option>
                                    @endif
                                </x-select>
                            </div>

                            <div class="col-span-6">
                                <label for="url" class="block text-sm font-medium text-slate-700 mb-1.5">个人主页</label>
                                <x-input type="url" name="url" id="url" autocomplete="url" value="{{ Auth::user()->url }}" placeholder="个人主页地址，http(s)://"/>
                            </div>

                            <div class="col-span-6">
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    密码
                                    <span class="text-slate-400 font-normal text-xs ml-1">（不修改请留空）</span>
                                </label>
                                <x-input type="password" name="password" id="password" placeholder="输入新密码" autocomplete="new-password" />
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="是否自动清除预览" faq="设置上传时，文件上传完成以后是否自动清除预览图片">
                                    <x-fieldset-radio id="is_auto_clear_preview_yes" name="configs[is_auto_clear_preview]" value="1" :checked="Auth::user()->configs->get('is_auto_clear_preview')">是</x-fieldset-radio>
                                    <x-fieldset-radio id="is_auto_clear_preview_no" name="configs[is_auto_clear_preview]" value="0" :checked="! Auth::user()->configs->get('is_auto_clear_preview')">否</x-fieldset-radio>
                                </x-fieldset>
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="图片粘贴后动作" faq="设置上传页面粘贴图片后的动作">
                                    <x-fieldset-radio id="pasted_action_upload" name="configs[pasted_action]" value="{{ \App\Enums\PastedAction::Upload }}" :checked="Auth::user()->configs->get('pasted_action') == \App\Enums\PastedAction::Upload">直接上传</x-fieldset-radio>
                                    <x-fieldset-radio id="pasted_action_waiting" name="configs[pasted_action]" value="{{ \App\Enums\PastedAction::Waiting }}" :checked="Auth::user()->configs->get('pasted_action') == \App\Enums\PastedAction::Waiting">等待上传</x-fieldset-radio>
                                </x-fieldset>
                            </div>

                            <div class="col-span-6">
                                <x-fieldset title="图片默认权限" faq="设置上传的图片默认的权限(公开还是私有，公开的图片将会出现在画廊中，你也可以通过图片管理单独设置权限)">
                                    <x-fieldset-radio id="default_permission_private" name="configs[default_permission]" value="{{ \App\Enums\ImagePermission::Private }}" :checked="Auth::user()->configs->get('default_permission') == \App\Enums\ImagePermission::Private">私有</x-fieldset-radio>
                                    <x-fieldset-radio id="default_permission_public" name="configs[default_permission]" value="{{ \App\Enums\ImagePermission::Public }}" :checked="Auth::user()->configs->get('default_permission') == \App\Enums\ImagePermission::Public">公开</x-fieldset-radio>
                                </x-fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 flex justify-end" style="background: var(--card-header-bg); border-top: 1px solid var(--border-strong);">
                        <x-button><i class="fas fa-save mr-1.5"></i>保存设置</x-button>
                    </div>
                </div>
            </form>
        </div>

        @if($showOauthSection)
            <div class="mt-8 w-full">
                <div class="mb-5 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, rgba(16,185,129,0.14), rgba(13,148,136,0.14)); box-shadow: inset 0 1px 0 rgba(255,255,255,0.45);">
                        <i class="fas fa-shield-alt text-sm" style="color: #059669;"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800 leading-none">OAuth 账号绑定</h2>
                        <p class="mt-2 text-sm text-slate-400">绑定后可直接使用 {{ $oauthProviderName }} 快捷登录当前账号。</p>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden" style="background: var(--panel-bg-strong); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow);">
                    <div class="px-6 py-5 space-y-4">
                    @if($oauthError)
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $oauthError }}
                        </div>
                    @elseif($oauthEnabled && ! $oauthTableExists)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                            OAuth 数据表不存在，请先执行数据库迁移后再使用账号绑定功能。
                        </div>
                    @endif

                    @if($hasOauthAccount)
                        <div class="space-y-3">
                            @foreach($oauthAccounts as $account)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-4 sm:px-5" style="box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);">
                                    <div class="flex flex-col gap-4 md:grid md:grid-cols-[minmax(0,1fr)_auto] md:items-center md:gap-6">
                                        <div class="min-w-0 flex items-start gap-3.5 sm:items-center">
                                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white" style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 6px 18px rgba(16,185,129,0.22);">
                                                <i class="fas fa-link text-sm"></i>
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-2.5">
                                                    <p class="truncate text-sm font-semibold text-slate-700">{{ $oauthProviderName }}</p>
                                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3.5 py-1 text-[11px] font-semibold text-emerald-600">已绑定</span>
                                                </div>
                                                <p class="mt-1 truncate text-sm text-slate-500 sm:text-[15px]">{{ $account->provider_user_name ?: $account->provider_user_id }}</p>
                                                @if($account->provider_user_email)
                                                    <p class="mt-1 truncate text-xs text-slate-400 sm:text-sm">{{ $account->provider_user_email }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex w-full items-center justify-end md:w-auto md:shrink-0">
                                            <button type="button" onclick="unbindOAuth({{ $account->id }})"
                                                class="inline-flex w-full items-center justify-center gap-1.5 py-2 px-5 text-sm font-semibold rounded-lg text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 sm:w-auto"
                                                style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 2px 10px rgba(16,185,129,0.3);">
                                                <i class="fas fa-unlink text-xs"></i>
                                                <span>解绑账号</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(! $oauthEnabled)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-5 py-6 text-center text-sm text-slate-400">
                            站点管理员已关闭 OAuth 登录功能。
                        </div>
                    @elseif(! $oauthTableExists)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 px-5 py-6 text-center text-sm text-slate-400">
                            完成数据库迁移后即可在这里绑定 OAuth 账号。
                        </div>
                    @else
                        <x-no-data message="暂未绑定 OAuth 账号，绑定后可使用快捷登录" />
                    @endif
                </div>

                <div class="px-6 py-4" style="background: var(--card-header-bg); border-top: 1px solid var(--border-strong);">
                    @if($hasOauthAccount)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs leading-6 text-slate-500 sm:flex-1 sm:text-sm">每个账户仅支持绑定一个 OAuth 账号，如需更换请先解绑当前账号。</p>
                            <span class="inline-flex w-full shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-700 sm:w-auto sm:px-5">
                                <i class="fas fa-check-circle"></i>
                                <span>已绑定</span>
                            </span>
                        </div>
                    @elseif(! $oauthEnabled)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-slate-500 sm:text-sm">启用后可在此绑定第三方账号并使用快捷登录。</p>
                            <span class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-500 sm:w-auto">
                                <i class="fas fa-ban"></i>
                                <span>暂不可用</span>
                            </span>
                        </div>
                    @elseif(! $oauthTableExists)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-amber-600 sm:text-sm">请先执行数据库迁移，完成后即可绑定 OAuth 账号。</p>
                            <span class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700 sm:w-auto">
                                <i class="fas fa-tools"></i>
                                <span>等待迁移</span>
                            </span>
                        </div>
                    @else
                        <div class="flex justify-end">
                            <a href="{{ route('oauth.bind.redirect') }}" class="inline-flex w-full items-center justify-center gap-1.5 py-2.5 px-5 text-sm font-semibold rounded-lg text-white transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 sm:w-auto" style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 2px 10px rgba(16,185,129,0.3);">
                                <i class="fas fa-plus"></i>
                                <span>绑定账号</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $('form').submit(function (e) {
                e.preventDefault();
                axios.put(this.action, $(this).serialize()).then(response => {
                    toastr[response.data.status ? 'success' : 'warning'](response.data.message);
                });
            });

            @if(session('oauth_status'))
            toastr.success(@json(session('oauth_status')));
            @endif

            @if($showOauthSection)
            function unbindOAuth(id) {
                Swal.fire({
                    title: '确认解绑',
                    text: '解绑后将无法使用该 OAuth 账号快捷登录，请输入密码确认',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off',
                        autocomplete: 'current-password',
                        placeholder: '请输入当前密码'
                    },
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '确认解绑',
                    cancelButtonText: '取消',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-[24px] border border-slate-200 shadow-2xl px-2 sm:px-3',
                        title: 'text-slate-800 text-xl font-bold',
                        htmlContainer: 'text-sm text-slate-500 leading-6',
                        input: 'mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20',
                        validationMessage: 'mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700',
                        actions: 'mt-6 flex w-full flex-col-reverse gap-3 sm:flex-row sm:justify-end',
                        confirmButton: 'inline-flex items-center justify-center rounded-lg py-2.5 px-5 text-sm font-semibold text-white transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-emerald-500/50',
                        cancelButton: 'inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white py-2.5 px-5 text-sm font-semibold text-slate-600 transition-all duration-200 hover:bg-slate-50'
                    },
                    showLoaderOnConfirm: true,
                    didOpen: () => {
                        const popup = Swal.getPopup();
                        if (popup) {
                            popup.style.background = 'linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.98))';
                        }
                        const confirmButton = Swal.getConfirmButton();
                        if (confirmButton) {
                            confirmButton.style.background = 'linear-gradient(135deg, #059669, #0d9488)';
                            confirmButton.style.boxShadow = '0 2px 10px rgba(16,185,129,0.3)';
                        }
                    },
                    preConfirm: (password) => {
                        const url = '{{ route('oauth.unbind', ['oauthAccount' => '__OAUTH_ACCOUNT__']) }}'.replace('__OAUTH_ACCOUNT__', id);
                        return axios.delete(url, { data: { password: password } })
                            .then(response => {
                                if (!response.data.status) throw new Error(response.data.message);
                                return response.data;
                            })
                            .catch(error => {
                                Swal.showValidationMessage(error.response?.data?.message || error.message);
                            });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        toastr.success(result.value.message);
                        setTimeout(() => location.reload(), 1500);
                    }
                });
            }
            @endif
        </script>
    @endpush

</x-app-layout>
