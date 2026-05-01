@section('title', '系统设置')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/markdown-css/github-markdown-light.css') }}">
@endpush

<x-app-layout>
    <div class="my-6 md:my-9">
        <p class="admin-section-title">通用</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>应用名称</label>
                    <x-input type="text" name="app_name" id="app_name" value="{{ $configs->get('app_name') }}" placeholder="请输入应用名称"/>
                </div>
                <div>
                    <label for="site_keywords" class="block text-sm font-medium text-slate-700">网站关键字</label>
                    <x-textarea type="text" name="site_keywords" id="site_keywords" placeholder="请输入网站关键字">{{ $configs->get('site_keywords') }}</x-textarea>
                </div>
                <div>
                    <label for="site_description" class="block text-sm font-medium text-slate-700">网站描述</label>
                    <x-textarea type="text" name="site_description" id="site_description" placeholder="请输入网站描述">{{ $configs->get('site_description') }}</x-textarea>
                </div>
                <div>
                    <label for="icp_no" class="block text-sm font-medium text-slate-700">备案号</label>
                    <x-input type="text" name="icp_no" id="icp_no" value="{{ $configs->get('icp_no') }}" placeholder="请输入备案号"/>
                </div>
                <div>
                    <label for="site_notice" class="block text-sm font-medium text-slate-700">网站公告</label>
                    <x-textarea type="text" name="site_notice" id="site_notice" placeholder="首页弹出公告，支持Markdown，不设置请留空" rows="7">{{ $configs->get('site_notice') }}</x-textarea>
                </div>

                <div class="text-right">
                    <x-button type="submit">保存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">控制</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <x-fieldset title="是否启用注册" faq="启用或关闭系统注册功能">
                    <x-switch name="is_enable_registration" value="1" :checked="(bool) $configs->get('is_enable_registration')" />
                </x-fieldset>
                <x-fieldset title="是否启用画廊" faq="启用或关闭画廊功能，画廊只有已登录的用户可见，画廊中的图片均为所有用户公开的图片">
                    <x-switch name="is_enable_gallery" value="1" :checked="(bool) $configs->get('is_enable_gallery')" />
                </x-fieldset>
                <x-fieldset title="是否启用接口" faq="启用或关闭接口功能，关闭后将无法通过接口上传图片、管理图片等操作">
                    <x-switch name="is_enable_api" value="1" :checked="(bool) $configs->get('is_enable_api')" />
                </x-fieldset>
                <x-fieldset title="是否允许游客上传" faq="启用或关闭游客上传功能，游客上传受「系统默认组」控制">
                    <x-switch name="is_allow_guest_upload" value="1" :checked="(bool) $configs->get('is_allow_guest_upload')" />
                </x-fieldset>
                <x-fieldset title="账号验证" faq="是否强制用户验证邮箱，开启后用户必须经过验证邮箱后才能上传图片，请确保邮件配置正常">
                    <x-switch name="is_user_need_verify" value="1" :checked="(bool) $configs->get('is_user_need_verify')" />
                </x-fieldset>
                <div class="text-right">
                    <x-button type="submit">保存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">OAuth 2.0 登录</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <input type="hidden" name="oauth_enable" value="0">
                <x-fieldset title="是否启用 OAuth 登录" faq="启用或关闭第三方 OAuth 2.0 登录功能">
                    <x-switch name="oauth_enable" value="1" :checked="(bool) $configs->get('oauth_enable')" />
                </x-fieldset>
                <input type="hidden" name="oauth_allow_register" value="0">
                <x-fieldset title="是否允许 OAuth 注册" faq="开启后，用户可通过 OAuth 直接注册并登录；关闭后，用户需先使用邮箱注册，然后在个人设置中绑定 OAuth 账号">
                    <x-switch name="oauth_allow_register" value="1" :checked="(bool) $configs->get('oauth_allow_register')" />
                </x-fieldset>
                <input type="hidden" name="oauth_pkce_enable" value="0">
                <x-fieldset title="是否启用 PKCE" faq="PKCE (Proof Key for Code Exchange) 增强授权码流程的安全性，推荐开启">
                    <x-switch name="oauth_pkce_enable" value="1" :checked="(bool) $configs->get('oauth_pkce_enable', true)" />
                </x-fieldset>
                <div>
                    <label for="oauth_provider_name" class="block text-sm font-medium text-slate-700">登录按钮显示名称</label>
                    <x-input type="text" name="oauth_provider_name" id="oauth_provider_name" value="{{ $configs->get('oauth_provider_name') }}" placeholder="例如：GitHub / Google / 企业 SSO"/>
                </div>
                <div>
                    <label for="oauth_client_id" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Client ID</label>
                    <x-input type="text" name="oauth_client_id" id="oauth_client_id" value="{{ $configs->get('oauth_client_id') }}" placeholder="OAuth 应用的 Client ID" autocomplete="off"/>
                </div>
                <div>
                    <label for="oauth_client_secret" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Client Secret</label>
                    <x-input type="password" name="oauth_client_secret" id="oauth_client_secret" value="{{ $configs->get('oauth_client_secret') }}" placeholder="OAuth 应用的 Client Secret" autocomplete="new-password"/>
                </div>
                <div>
                    <label for="oauth_authorize_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>授权地址</label>
                    <x-input type="url" name="oauth_authorize_url" id="oauth_authorize_url" value="{{ $configs->get('oauth_authorize_url') }}" placeholder="https://provider.example.com/oauth/authorize"/>
                </div>
                <div>
                    <label for="oauth_token_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>Token 地址</label>
                    <x-input type="url" name="oauth_token_url" id="oauth_token_url" value="{{ $configs->get('oauth_token_url') }}" placeholder="https://provider.example.com/oauth/token"/>
                </div>
                <div>
                    <label for="oauth_userinfo_url" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>用户信息地址</label>
                    <x-input type="url" name="oauth_userinfo_url" id="oauth_userinfo_url" value="{{ $configs->get('oauth_userinfo_url') }}" placeholder="https://provider.example.com/oauth/userinfo"/>
                </div>
                <div class="rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700 space-y-2">
                    <p class="font-medium">请在 OAuth 提供方后台配置以下回调地址：</p>
                    <div>
                        <p class="text-xs text-sky-600 mb-1">登录回调</p>
                        <x-input type="text" :value="route('oauth.callback')" readonly onclick="this.select()" />
                    </div>
                    <div>
                        <p class="text-xs text-sky-600 mb-1">绑定回调</p>
                        <x-input type="text" :value="route('oauth.bind.callback')" readonly onclick="this.select()" />
                    </div>
                </div>

                <div>
                    <label for="oauth_scope" class="block text-sm font-medium text-slate-700">Scope</label>
                    <x-input type="text" name="oauth_scope" id="oauth_scope" value="{{ $configs->get('oauth_scope', 'openid profile email') }}" placeholder="openid profile email"/>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="oauth_user_id_field" class="block text-sm font-medium text-slate-700">用户标识字段</label>
                        <x-input type="text" name="oauth_user_id_field" id="oauth_user_id_field" value="{{ $configs->get('oauth_user_id_field', 'sub') }}" placeholder="sub"/>
                    </div>
                    <div>
                        <label for="oauth_user_name_field" class="block text-sm font-medium text-slate-700">用户名字段</label>
                        <x-input type="text" name="oauth_user_name_field" id="oauth_user_name_field" value="{{ $configs->get('oauth_user_name_field', 'name') }}" placeholder="name"/>
                    </div>
                    <div>
                        <label for="oauth_user_email_field" class="block text-sm font-medium text-slate-700">邮箱字段</label>
                        <x-input type="text" name="oauth_user_email_field" id="oauth_user_email_field" value="{{ $configs->get('oauth_user_email_field', 'email') }}" placeholder="email"/>
                    </div>
                </div>

                <div class="text-right">
                    <x-button type="submit">保存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">用户</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="user_initial_capacity" class="block text-sm font-medium text-slate-700">用户初始容量(kb)</label>
                    <x-input type="number" name="user_initial_capacity" id="user_initial_capacity" step="0.01" value="{{ $configs->get('user_initial_capacity') }}" placeholder="请输入用户初始容量(kb)"/>
                </div>

                <div class="text-right">
                    <x-button type="submit">保存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">自定义代码</p>
        <form action="{{ route('admin.settings.save') }}">
            <div class="admin-section space-y-4">
                <div>
                    <label for="custom_css" class="block text-sm font-medium text-slate-700">自定义 CSS</label>
                    <p class="text-xs text-slate-400 mb-1.5">自定义样式将注入到所有页面的 &lt;head&gt; 中，无需包裹 &lt;style&gt; 标签</p>
                    <x-textarea name="custom_css" id="custom_css" placeholder="/* 例如：修改主题色 */&#10;:root { --primary: #ef4444; }" rows="8" spellcheck="false">{{ $configs->get('custom_css') }}</x-textarea>
                </div>
                <div>
                    <label for="custom_js" class="block text-sm font-medium text-slate-700">自定义 JavaScript</label>
                    <p class="text-xs text-slate-400 mb-1.5">自定义脚本将注入到所有页面的底部，无需包裹 &lt;script&gt; 标签</p>
                    <x-textarea name="custom_js" id="custom_js" placeholder="// 例如：添加统计代码&#10;console.log('Hello Lsky Pro');" rows="8" spellcheck="false">{{ $configs->get('custom_js') }}</x-textarea>
                </div>

                <div class="text-right">
                    <x-button type="submit">保存更改</x-button>
                </div>
            </div>
        </form>

        <p class="admin-section-title">邮件配置</p>
        <div class="admin-section space-y-4">
            <x-fieldset title="发信驱动">
                <x-fieldset-radio id="mail[default]" name="mail[default]" data-select="mailer" value="smtp" checked>SMTP</x-fieldset-radio>
            </x-fieldset>

            <div class="mb-4 hidden" data-mailer-driver="smtp">
                <form action="{{ route('admin.settings.save') }}" class="space-y-4">
                    <div>
                        <label for="mail[mailers][smtp][host]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>主机地址</label>
                        <x-input type="text" name="mail[mailers][smtp][host]" id="mail[mailers][smtp][host]" value="{{ $configs['mail']['mailers']['smtp']['host'] ?? '' }}" placeholder="请输入SMTP 主机地址"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][port]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>连接端口</label>
                        <x-input type="number" name="mail[mailers][smtp][port]" id="mail[mailers][smtp][port]" value="{{ $configs['mail']['mailers']['smtp']['port'] ?? 587 }}" placeholder="请输入SMTP 主机连接端口"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][username]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>用户名</label>
                        <x-input type="text" name="mail[mailers][smtp][username]" id="mail[mailers][smtp][username]" value="{{ $configs['mail']['mailers']['smtp']['username'] ?? '' }}" placeholder="请输入用户名" autocomplete="off"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][password]" class="block text-sm font-medium text-slate-700"><span class="text-red-600">*</span>密码</label>
                        <x-input type="password" name="mail[mailers][smtp][password]" id="mail[mailers][smtp][password]" value="{{ $configs['mail']['mailers']['smtp']['password'] ?? '' }}" placeholder="请输入密码" autocomplete="new-password"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][encryption]" class="block text-sm font-medium text-slate-700">加密方式</label>
                        <x-input type="text" name="mail[mailers][smtp][encryption]" id="mail[mailers][smtp][encryption]" value="{{ $configs['mail']['mailers']['smtp']['encryption'] ?? '' }}" placeholder="请输入加密方式(ssl, tls)"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][timeout]" class="block text-sm font-medium text-slate-700">连接超时时间(秒)</label>
                        <x-input type="number" name="mail[mailers][smtp][timeout]" id="mail[mailers][smtp][timeout]" value="{{ $configs['mail']['mailers']['smtp']['timeout'] ?? 10 }}" placeholder="请输入连接超时时间(秒)"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][from_address]" class="block text-sm font-medium text-slate-700">发件人地址</label>
                        <x-input type="email" name="mail[from][address]" id="mail[from][address]" value="{{ $configs['mail']['from']['address'] ?? '' }}" placeholder="请输入发件人邮箱地址"/>
                    </div>
                    <div>
                        <label for="mail[mailers][smtp][from_name]" class="block text-sm font-medium text-slate-700">发件人名称</label>
                        <x-input type="text" name="mail[from][name]" id="mail[from][name]" value="{{ $configs['mail']['from']['name'] ?? '' }}" placeholder="请输入发件人名称"/>
                    </div>

                    <input type="hidden" name="mail[default]" value="smtp">
                    <input type="hidden" name="mail[mailers][smtp][transport]" value="smtp">

                    <div class="text-right">
                        <x-button type="button" id="mail-test" class="bg-yellow-500">测试</x-button>
                        <x-button type="submit">保存更改</x-button>
                    </div>
                </form>
            </div>
        </div>

        <p class="admin-section-title">系统升级</p>
        <div class="admin-section">
            <p id="check-update" class="text-gray-600 text-center p-4" style="display: none">
                <i class="fas fa-cog animate-spin"></i> 正在检查更新...
            </p>
            <p id="not-update" class="text-center p-6" style="display: none">
            <span class="text-slate-700">{{ \App\Utils::config(\App\Enums\ConfigKey::AppVersion) }}</span>
                <span class="text-slate-500">已是最新版本</span>
            </p>
            <div id="have-update" class="break-words" style="display: none"></div>
        </div>
    </div>

    <script type="text/html" id="update-tpl">
        <div class="flex items-center">
            <img id="icon" src="__icon__" alt="icon" class="rounded-full w-16" style="animation-duration: 5s">
            <div class="flex flex-col text-slate-700 ml-4">
                <p class="font-semibold">Lsky Pro __name__</p>
                <p class="text-sm">__size__</p>
                <p class="text-sm">发布于 __pushed_at__</p>
            </div>
        </div>
        <p id="upgrade-message" class="mt-4 text-sm text-gray-500"></p>
        <div class="mt-4 text-sm markdown-body">
            __changelog__
        </div>
        <div class="mt-6 text-right">
            <a href="javascript:void(0)" id="install" class="rounded-md px-4 py-2 bg-blue-500 text-white">立即安装</a>
        </div>
    </script>

    @push('scripts')
        <script>
            // 设置选中驱动
            let setSelected = function () {
                $('[data-select]').each(function () {
                    $(`[data-${$(this).data('select')}-driver=${$(this).val()}]`)[this.checked ? 'show' : 'hide']();
                });
            };

            setSelected();

            $('[data-select]').click(function () {
                setSelected();
            });

            $('form').submit(function (e) {
                e.preventDefault();
                axios.put(this.action, $(this).serialize()).then(function (response) {
                    toastr[response.data.status ? 'success' : 'error'](response.data.message)
                });
            });

            $('#mail-test').click(function () {
                Swal.fire({
                    title: '请输入接收测试邮件的邮箱',
                    input: 'text',
                    inputValue: '',
                    inputAttributes: {
                        type: 'email',
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: '确认',
                    showLoaderOnConfirm: true,
                    preConfirm: (value) => {
                        return axios.post('{{ route('admin.settings.mail.test') }}', {
                            email: value,
                        }).then(response => {
                            if (! response.data.status) {
                                throw new Error(response.data.message)
                            }
                            return response.data;
                        }).catch(error => Swal.showValidationMessage(error));
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        toastr[result.value.status ? 'success' : 'warning'](result.value.message);
                    }
                })
            });

            let timer;
            let upgrade = function () {
                return {
                    start: function () {
                        $('#icon').addClass('animate-spin')
                        $('#install').attr('disabled', true).removeClass('bg-blue-500').addClass('cursor-not-allowed bg-gray-400').text('执行升级中...')
                        $('#upgrade-message').text('准备升级...').removeClass('text-red-500').addClass('text-gray-500');

                        timer = setInterval(getProgress, 1500);
                        axios.post('{{ route('admin.settings.upgrade') }}');
                    },
                    stop: function () {
                        $('#icon').removeClass('animate-spin')
                        $('#install').attr('disabled', false).removeClass('cursor-not-allowed bg-gray-400').addClass('bg-blue-500').text('立即安装')
                        clearInterval(timer);
                    }
                };
            };

            let getVersion = function (callback) {
                $('#check-update').show();
                axios.get('{{ route('admin.settings.check.update') }}').then(response => {
                    if (response.data.status && response.data.data.is_update) {
                        $('#check-update').hide();
                        let version = response.data.data.version;
                        let html = $('#update-tpl').html()
                            .replace(/__icon__/g, version.icon)
                            .replace(/__name__/g, version.name)
                            .replace(/__size__/g, version.size)
                            .replace(/__pushed_at__/g, version.pushed_at)
                            .replace(/__changelog__/g, version.changelog);
                        $('#have-update').html(html).show();
                        $('.markdown-body a').attr('target', '_blank');
                        callback && callback(version);
                    } else {
                        $('#not-update').show();
                        $('#check-update').hide();
                    }
                });
            }

            let getProgress = function () {
                axios.get('{{ route('admin.settings.upgrade.progress') }}').then(response => {
                    $('#upgrade-message').text(response.data.data.message);
                    if (response.data.data.status === 'success') {
                        $('#upgrade-message').removeClass('text-gray-500').addClass('text-green-500');
                        $('#install').hide();
                    }
                    if (response.data.data.status === 'fail') {
                        $('#upgrade-message').removeClass('text-gray-500').addClass('text-red-500');
                    }
                    if (response.data.data.status !== 'installing') {
                        upgrade().stop();
                    }
                });
            };

            $(document).on('click', '#install', function () {
                if ($(this).attr('disabled')) {
                    return;
                }
                upgrade().start();
            });

            @if(cache()->has('upgrade_progress'))
                getVersion(() => {
                    $('#icon').addClass('animate-spin')
                    $('#install').attr('disabled', true).removeClass('bg-blue-500').addClass('cursor-not-allowed bg-gray-400').text('正在升级...')
                    $('#upgrade-message').text('请稍等...').removeClass('text-red-500').addClass('text-gray-500');

                    timer = setInterval(getProgress, 1500);
                });
                @else
                getVersion();
            @endif
        </script>
    @endpush

</x-app-layout>
