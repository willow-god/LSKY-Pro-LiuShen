@section('title', '系统控制台')

@push('styles')
    <style>
        html.dark .stat-card:nth-child(-n+4) {
            border-color: rgba(51,65,85,0.92) !important;
            box-shadow: 0 14px 34px rgba(0,0,0,0.24) !important;
        }

        html.dark .stat-card:nth-child(1) { background: linear-gradient(135deg, rgba(6,78,59,0.88), rgba(15,118,110,0.84)) !important; }
        html.dark .stat-card:nth-child(2) { background: linear-gradient(135deg, rgba(12,74,110,0.88), rgba(14,116,144,0.82)) !important; }
        html.dark .stat-card:nth-child(3) { background: linear-gradient(135deg, rgba(19,78,74,0.88), rgba(13,148,136,0.8)) !important; }
        html.dark .stat-card:nth-child(4) { background: linear-gradient(135deg, rgba(15,23,42,0.96), rgba(30,41,59,0.92)) !important; }

        html.dark .stat-card:nth-child(-n+4) p,
        html.dark .stat-card:nth-child(-n+4) i {
            color: #e2e8f0 !important;
        }

        html.dark .stat-card:nth-child(n+5) {
            background: var(--card-bg) !important;
            border: 1px solid rgba(51,65,85,0.88);
        }
    </style>
@endpush

<x-app-layout>
    @if(config('app.debug'))
        <p class="mt-4 p-2 rounded-md text-sm bg-red-500 text-white">
            <i class="fas fa-exclamation-triangle"></i>
            当前系统 debug 已被打开，敏感信息暴露在外，可能会被利用从而影响系统稳定性，生产环境中请务必关闭！
        </p>
    @endif
    <div class="my-6 md:my-9">
        <p class="admin-section-title">概览</p>
        <div class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- 图片数量 --}}
            <div class="stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border: 1px solid rgba(16,185,129,0.2); box-shadow: 0 4px 20px rgba(16,185,129,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.15);">
                    <i class="fas fa-images text-xl" style="color: #059669;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">图片数量</p>
                    <p class="font-bold text-2xl mt-0.5" style="color: #065f46;">{{ \App\Utils::shortenNumber(\App\Models\Image::query()->count()) }}</p>
                </div>
            </div>
            {{-- 相册数量 --}}
            <div class="stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid rgba(14,165,233,0.2); box-shadow: 0 4px 20px rgba(14,165,233,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(14,165,233,0.15);">
                    <i class="fas fa-tags text-xl" style="color: #0284c7;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">相册数量</p>
                    <p class="font-bold text-2xl mt-0.5" style="color: #0c4a6e;">{{ \App\Utils::shortenNumber(\App\Models\Album::query()->count()) }}</p>
                </div>
            </div>
            {{-- 用户数量 --}}
            <div class="stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f0fdfa, #ccfbf1); border: 1px solid rgba(20,184,166,0.2); box-shadow: 0 4px 20px rgba(20,184,166,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(20,184,166,0.15);">
                    <i class="fas fa-users text-xl" style="color: #0d9488;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">用户数量</p>
                    <p class="font-bold text-2xl mt-0.5" style="color: #134e4a;">{{ \App\Utils::shortenNumber(\App\Models\User::query()->count()) }}</p>
                </div>
            </div>
            {{-- 占用储存 --}}
            <div class="stat-card rounded-2xl p-5 flex items-center gap-4 relative overflow-hidden" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid rgba(100,116,139,0.18); box-shadow: 0 4px 20px rgba(100,116,139,0.1);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(100,116,139,0.1);">
                    <i class="fas fa-server text-xl" style="color: #64748b;"></i>
                </div>
                <div>
                    <p class="text-xs font-medium" style="color: #6b7280;">占用储存</p>
                    <p class="font-bold text-xl mt-0.5 leading-tight" style="color: #1e293b;">{{ \App\Utils::formatSize(\App\Models\Image::query()->sum('size') * 1024) }}</p>
                </div>
            </div>

            {{-- 上传统计 4 项 --}}
            @foreach([['today','今日上传'],['yesterday','昨日上传'],['week','本周上传'],['month','本月上传']] as [$key,$label])
            <div class="stat-card flex items-center gap-4 rounded-xl bg-white p-4 shadow-custom" style="transition: transform 0.25s cubic-bezier(0.4,0,0.2,1), box-shadow 0.25s cubic-bezier(0.4,0,0.2,1);">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.08);">
                    <i class="fas fa-upload text-emerald-500"></i>
                </div>
                <div>
                    <p class="font-bold text-xl text-slate-700">{{ \App\Utils::shortenNumber($numbers[$key]) }}</p>
                    <p class="text-sm text-slate-500">{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <p class="admin-section-title">趋势</p>
        <div class="admin-section h-80 p-4" id="chart">
            <canvas></canvas>
        </div>

        <p class="admin-section-title">系统情况</p>
        <div class="admin-section !p-0 overflow-hidden">
            <dl>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">操作系统</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ php_uname() }}
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">运行环境</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ request()->server('SERVER_SOFTWARE') }}
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">PHP 版本</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ phpversion() }}
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">文件上传限制</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ ini_get("upload_max_filesize") }}
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">POST 数据最大限制</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ ini_get('post_max_size') }}
                    </dd>
                </div>
            </dl>
        </div>

        <p class="admin-section-title">软件信息</p>
        <div class="admin-section !p-0 overflow-hidden">
            <dl>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">软件版本</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">{{ \App\Utils::config(\App\Enums\ConfigKey::AppVersion) }}</dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">官方网站</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-emerald-500 hover:text-emerald-600" href="https://www.lsky.pro">https://www.lsky.pro</a>
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">使用手册</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-emerald-500 hover:text-emerald-600" href="https://docs.lsky.pro">https://docs.lsky.pro</a>
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">仓库地址</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-emerald-500 hover:text-emerald-600" href="https://github.com/lsky-org/lsky-pro">https://github.com/lsky-org/lsky-pro</a>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/echarts/echarts.min.js') }}"></script>
        <script>
            $(function () {
                'use strict'
                let chartDom = document.getElementById('chart');
                let myChart = echarts.init(chartDom);
                let options;

                options = {
                    responsive: true,
                    title: {
                        text: '近 30 天内统计'
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        top: '10%',
                        type: 'scroll',
                        data: @json($fields)
                    },
                    grid: {
                        left: '3%',
                        right: '3%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            magicType: {
                                type: ["line", "bar"]
                            },
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: @json($dates)
                    },
                    yAxis: {
                        type: 'value',
                        minInterval: 1,
                    },
                    series: @json($datasets)
                };

                options && myChart.setOption(options);

                window.onresize = function() {
                    myChart.resize();
                }
            })
        </script>
    @endpush

</x-app-layout>
