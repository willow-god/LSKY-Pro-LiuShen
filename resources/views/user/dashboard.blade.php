@section('title', '仪表盘')

@push('styles')
    <style>
        html.dark .dashboard-stat-card {
            border-color: rgba(51,65,85,0.92) !important;
            box-shadow: 0 14px 34px rgba(0,0,0,0.24) !important;
        }

        html.dark .dashboard-stat-card:nth-child(1) { background: linear-gradient(135deg, rgba(6,78,59,0.88), rgba(15,118,110,0.84)) !important; }
        html.dark .dashboard-stat-card:nth-child(2) { background: linear-gradient(135deg, rgba(12,74,110,0.88), rgba(14,116,144,0.82)) !important; }
        html.dark .dashboard-stat-card:nth-child(3) { background: linear-gradient(135deg, rgba(127,29,29,0.86), rgba(153,27,27,0.78)) !important; }
        html.dark .dashboard-stat-card:nth-child(4) { background: linear-gradient(135deg, rgba(92,77,12,0.82), rgba(120,113,28,0.74)) !important; }

        html.dark .dashboard-stat-card p,
        html.dark .dashboard-stat-card i {
            color: #e2e8f0 !important;
        }

        html.dark .dashboard-capacity-card {
            background: var(--card-bg) !important;
            border-color: rgba(51,65,85,0.9) !important;
            box-shadow: 0 12px 32px rgba(0,0,0,0.26) !important;
        }

        html.dark .dashboard-capacity-track {
            background: rgba(30,41,59,0.92) !important;
        }
    </style>
@endpush

<x-app-layout>
    <div class="my-6 md:my-8 space-y-6">
        {{-- 统计卡片 --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- 图片数量 --}}
            <div class="dashboard-stat-card stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid rgba(16,185,129,0.2); box-shadow: 0 4px 20px rgba(16,185,129,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.15);">
                    <i class="fas fa-images text-xl" style="color: #059669;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">图片数量</p>
                    <p class="font-bold text-2xl mt-0.5" style="color: #065f46;">{{ $user->image_num }}</p>
                </div>
            </div>

            {{-- 可用储存 --}}
            <div class="dashboard-stat-card stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid rgba(14,165,233,0.2); box-shadow: 0 4px 20px rgba(14,165,233,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(14,165,233,0.15);">
                    <i class="fas fa-database text-xl" style="color: #0284c7;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">可用储存</p>
                    <p class="font-bold text-xl mt-0.5 leading-tight" style="color: #0c4a6e;">{{ \App\Utils::formatSize(($user->capacity - $user->use_capacity) * 1024) }}</p>
                </div>
            </div>

            {{-- 已用储存 --}}
            <div class="dashboard-stat-card stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdfa, #fbcccc); border: 1px solid rgba(184, 83, 20, 0.2); box-shadow: 0 4px 20px rgba(20,184,166,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(184, 20, 20, 0.15);">
                    <i class="fas fa-hdd text-xl" style="color: #940d0d;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">使用储存</p>
                    <p class="font-bold text-xl mt-0.5 leading-tight" style="color: #4e1313;">{{ \App\Utils::formatSize($user->use_capacity * 1024) }}</p>
                </div>
            </div>

            {{-- 总储存 --}}
            <div class="dashboard-stat-card stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc, #fffde8); border: 1px solid rgba(139, 138, 100, 0.18); box-shadow: 0 4px 20px rgba(100,116,139,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(100,116,139,0.1);">
                    <i class="fas fa-server text-xl" style="color: #64748b;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">总储存</p>
                    <p class="font-bold text-xl mt-0.5 leading-tight" style="color: #3b371e;">{{ \App\Utils::formatSize($user->capacity * 1024) }}</p>
                </div>
            </div>
        </div>

        {{-- 容量使用进度条 --}}
        @php
            $usedKB   = $user->use_capacity;
            $totalKB  = $user->capacity > 0 ? $user->capacity : 1;
            $pct      = min(100, round($usedKB / $totalKB * 100, 1));
            $barColor = $pct >= 90 ? 'linear-gradient(90deg,#ef4444,#f97316)' : ($pct >= 70 ? 'linear-gradient(90deg,#f97316,#fbbf24)' : 'linear-gradient(90deg,#10b981,#0d9488)');
        @endphp
        <div class="dashboard-capacity-card rounded-2xl p-5" style="background: var(--panel-bg-strong); border: 1px solid var(--border-strong); box-shadow: var(--card-shadow-hover);">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(16,185,129,0.1);">
                        <i class="fas fa-tachometer-alt text-xs" style="color: #10b981;"></i>
                    </div>
                    <span class="font-semibold text-slate-700 text-sm">储存空间使用情况</span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold {{ $pct >= 90 ? 'text-red-500' : ($pct >= 70 ? 'text-orange-500' : 'text-emerald-600') }}">{{ $pct }}%</span>
                    <span class="text-slate-400 text-xs ml-1">已使用</span>
                </div>
            </div>
            <div class="dashboard-capacity-track h-2.5 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-xs text-slate-400">已用 <span class="text-slate-600 font-medium">{{ \App\Utils::formatSize($usedKB * 1024) }}</span></span>
                <span class="text-xs text-slate-400">共 <span class="text-slate-600 font-medium">{{ \App\Utils::formatSize($totalKB * 1024) }}</span></span>
            </div>
        </div>

        {{-- 主内容区 --}}
        <div class="flex flex-col md:flex-row gap-5">
            {{-- 可用策略 --}}
            <div class="flex-1">
                <x-box>
                    <x-slot name="title">可使用的储存策略</x-slot>
                    <x-slot name="content">
                        @if($strategies->isEmpty())
                            <x-no-data message="您所在的组还没有可用的储存策略，请联系管理员。" />
                        @else
                            <div class="divide-y divide-slate-100/80">
                                @foreach ($strategies as $strategy)
                                    <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-emerald-50/50 transition-colors duration-150">
                                        <div class="mt-0.5 w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.1);">
                                            <i class="fas fa-server text-xs" style="color: #10b981;"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-slate-800 font-semibold text-sm">{{ $strategy->name }}</p>
                                            <span class="text-slate-400 text-xs mt-0.5 block truncate">{{ $strategy->intro ?: '暂无描述' }}</span>
                                        </div>
                                        <div class="flex-shrink-0 w-1.5 h-1.5 rounded-full mt-2 bg-emerald-400"></div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-slot>
                </x-box>
            </div>

            {{-- 右侧信息栏 --}}
            <div class="flex flex-col md:w-[40%] xl:w-[35%] gap-5">
                {{-- 我的信息 --}}
                <x-box>
                    <x-slot name="title">我的信息</x-slot>
                    <x-slot name="content">
                        <div class="px-5 py-4 space-y-0">
                            @php $infoItems = [
                                ['icon' => 'fa-user-circle', 'label' => '姓名',     'value' => $user->name],
                                ['icon' => 'fa-envelope',   'label' => '邮箱',     'value' => $user->email],
                                ['icon' => 'fa-calendar',   'label' => '注册时间', 'value' => $user->created_at],
                                ['icon' => 'fa-map-marker-alt','label'=>'注册 IP', 'value' => $user->registered_ip],
                            ]; @endphp
                            @foreach($infoItems as $i => $item)
                            <div class="flex items-center gap-3 py-2.5 {{ $i < count($infoItems)-1 ? 'border-b border-slate-100/80' : '' }}">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.08);">
                                    <i class="fas {{ $item['icon'] }} text-xs" style="color: #059669;"></i>
                                </div>
                                <div class="flex-1 min-w-0 flex items-center justify-between gap-2">
                                    <p class="text-slate-400 text-xs flex-shrink-0">{{ $item['label'] }}</p>
                                    <p class="text-slate-700 text-sm font-medium truncate text-right">{{ $item['value'] }}</p>
                                </div>
                            </div>
                            @endforeach
                            @if(\App\Utils::config(\App\Enums\ConfigKey::IsUserNeedVerify) && !$user->email_verified_at)
                                <div class="mt-3 p-3 text-sm rounded-xl text-white flex items-start gap-2" style="background: linear-gradient(135deg, #f97316, #ef4444);">
                                    <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                                    <span>账号尚未激活，请点击
                                        <a id="send-verify-email" href="javascript:void(0)" class="underline font-semibold">重新发送</a>激活邮件。
                                    </span>
                                </div>
                            @endif
                        </div>
                    </x-slot>
                </x-box>

                {{-- 角色组信息 --}}
                <x-box>
                    <x-slot name="title">角色组信息</x-slot>
                    <x-slot name="content">
                        <div class="px-5 py-4 space-y-0">
                            @php $groupItems = [
                                ['icon' => 'fa-layer-group',  'label' => '组名',       'value' => $user->group ? $user->group->name : '系统默认组'],
                                ['icon' => 'fa-file-image',   'label' => '最大文件',   'value' => \App\Utils::formatSize($configs->get(\App\Enums\GroupConfigKey::MaximumFileSize) * 1024)],
                                ['icon' => 'fa-upload',       'label' => '并发上传',   'value' => $configs->get(\App\Enums\GroupConfigKey::ConcurrentUploadNum) . ' 张'],
                                ['icon' => 'fa-clock',        'label' => '每分钟限制', 'value' => $configs->get(\App\Enums\GroupConfigKey::LimitPerMinute) . ' 张'],
                                ['icon' => 'fa-hourglass-half','label' => '每小时限制','value' => $configs->get(\App\Enums\GroupConfigKey::LimitPerHour) . ' 张'],
                                ['icon' => 'fa-sun',          'label' => '每天限制',   'value' => $configs->get(\App\Enums\GroupConfigKey::LimitPerDay) . ' 张'],
                                ['icon' => 'fa-calendar-week','label' => '每周限制',   'value' => $configs->get(\App\Enums\GroupConfigKey::LimitPerWeek) . ' 张'],
                                ['icon' => 'fa-calendar-alt', 'label' => '每月限制',   'value' => $configs->get(\App\Enums\GroupConfigKey::LimitPerMonth) . ' 张'],
                            ]; @endphp
                            @foreach($groupItems as $i => $item)
                            <div class="flex items-center gap-3 py-2.5 {{ $i < count($groupItems)-1 ? 'border-b border-slate-100/80' : '' }}">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.08);">
                                    <i class="fas {{ $item['icon'] }} text-xs" style="color: #059669;"></i>
                                </div>
                                <div class="flex-1 min-w-0 flex items-center justify-between gap-2">
                                    <p class="text-slate-400 text-xs flex-shrink-0">{{ $item['label'] }}</p>
                                    <p class="text-slate-700 text-sm font-medium truncate text-right">{{ $item['value'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </x-slot>
                </x-box>
            </div>
        </div>
    </div>

    @if(\App\Utils::config(\App\Enums\ConfigKey::IsUserNeedVerify) && !$user->email_verified_at)
        @push('scripts')
            <script>
                $('#send-verify-email').click(function () {
                    if (! $(this).attr('disabled')) {
                        $(this).text('发送中...').attr('disabled');
                        axios.post('{{ route('verification.send') }}').then(response => {
                            toastr.success('发送成功，请注意查收。');
                        }).catch(error => {
                            if (error.response.status === 429) {
                                toastr.error('操作频繁，请稍后再试');
                            }
                        }).finally(_ => {
                            $(this).text('这里').attr('disabled');
                        });
                    }
                });
            </script>
        @endpush
    @endif
</x-app-layout>
