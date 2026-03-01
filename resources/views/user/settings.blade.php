@section('title', '设置')

<x-app-layout>
    <div class="my-6 md:my-8 max-w-3xl">
        <div class="mb-5 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                <i class="fas fa-user-cog text-sm" style="color: #059669;"></i>
            </div>
            <h2 class="font-bold text-lg text-slate-800">基础设置</h2>
        </div>

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="rounded-2xl overflow-hidden" style="background: white; border: 1px solid rgba(226,232,240,0.8); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(16,185,129,0.05);">
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
                <div class="px-6 py-4 flex justify-end" style="background: rgba(248,250,252,0.8); border-top: 1px solid rgba(226,232,240,0.8);">
                    <x-button><i class="fas fa-save mr-1.5"></i>保存设置</x-button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $('form').submit(function (e) {
                e.preventDefault();
                axios.put(this.action, $(this).serialize()).then(response => {
                    toastr[response.data.status ? 'success' : 'warning'](response.data.message);
                });
            });
        </script>
    @endpush

</x-app-layout>
