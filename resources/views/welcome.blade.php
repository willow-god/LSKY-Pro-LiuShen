@push('styles')
    <link rel="stylesheet" href="{{ asset('css/markdown-css/github-markdown-light.css') }}">
    <style>
        /* =========== 背景基底 =========== */
        .hero-bg {
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        /* 层 1：中心徑向晕染 — 中心绿色调向外渐趋白 */
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 78% 70% at 50% 44%,
                    rgba(167,243,208,0.60) 0%,
                    rgba(167,243,208,0.32) 22%,
                    rgba(153,246,228,0.15) 42%,
                    rgba(204,251,241,0.06) 60%,
                    transparent 75%);
            pointer-events: none;
        }

        /* 层 2：四角装饰性大光晕，增加空间层次 */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle 600px at 8% 18%,  rgba(16,185,129,0.07) 0%, transparent 100%),
                radial-gradient(circle 500px at 95% 82%, rgba(13,148,136,0.06) 0%, transparent 100%),
                radial-gradient(circle 420px at 88% 8%,  rgba(14,165,233,0.05) 0%, transparent 100%),
                radial-gradient(circle 380px at 3%  90%, rgba(16,185,129,0.04) 0%, transparent 100%);
            pointer-events: none;
        }

        /* 层 3：圆点网格 — 中心透明、四周显现（mask 反向） */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(5,150,105,0.28) 1px, transparent 1px);
            background-size: 26px 26px;
            -webkit-mask-image: radial-gradient(ellipse 82% 74% at 50% 44%,
                transparent 0%,
                rgba(0,0,0,0.08) 30%,
                rgba(0,0,0,0.35) 52%,
                rgba(0,0,0,0.70) 70%,
                rgba(0,0,0,0.90) 85%,
                black 100%);
            mask-image: radial-gradient(ellipse 82% 74% at 50% 44%,
                transparent 0%,
                rgba(0,0,0,0.08) 30%,
                rgba(0,0,0,0.35) 52%,
                rgba(0,0,0,0.70) 70%,
                rgba(0,0,0,0.90) 85%,
                black 100%);
            pointer-events: none;
        }

        /* 层 4：SVG feTurbulence 噪点纹理，增加 paper 质感 */
        .hero-noise {
            position: absolute;
            inset: 0;
            opacity: 0.38;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='260' height='260'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.72' numOctaves='4' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='260' height='260' filter='url(%23n)' opacity='0.09'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 220px 220px;
            pointer-events: none;
            mix-blend-mode: multiply;
        }

        .nav-link-hover {
            transition: all 0.2s;
        }
        .nav-link-hover:hover {
            color: #059669;
            background: rgba(16,185,129,0.06);
        }
        .upload-section {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(16,185,129,0.12);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 40px rgba(16,185,129,0.08), 0 2px 12px rgba(0,0,0,0.04);
        }
        .feature-pill {
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(16,185,129,0.15);
            color: #475569;
        }
        .feature-pill:hover {
            background: rgba(16,185,129,0.06);
            border-color: rgba(16,185,129,0.3);
            color: #059669;
        }

        html.dark .hero-bg {
            background: radial-gradient(circle at top, rgba(16,185,129,0.08), transparent 35%), #020617;
        }

        html.dark .hero-bg::before {
            background:
                radial-gradient(ellipse 78% 70% at 50% 44%,
                    rgba(16,185,129,0.18) 0%,
                    rgba(13,148,136,0.12) 28%,
                    rgba(14,165,233,0.08) 48%,
                    rgba(15,23,42,0.03) 62%,
                    transparent 78%);
        }

        html.dark .hero-bg::after {
            background:
                radial-gradient(circle 600px at 8% 18%, rgba(16,185,129,0.12) 0%, transparent 100%),
                radial-gradient(circle 500px at 95% 82%, rgba(13,148,136,0.10) 0%, transparent 100%),
                radial-gradient(circle 420px at 88% 8%, rgba(14,165,233,0.09) 0%, transparent 100%),
                radial-gradient(circle 380px at 3% 90%, rgba(16,185,129,0.08) 0%, transparent 100%);
        }

        html.dark .grid-pattern {
            background-image: radial-gradient(circle, rgba(148,163,184,0.18) 1px, transparent 1px);
        }

        html.dark .hero-noise {
            opacity: 0.22;
            mix-blend-mode: screen;
        }

        html.dark .nav-link-hover:hover {
            color: #34d399;
            background: rgba(16,185,129,0.12);
        }

        html.dark .upload-section {
            background: rgba(15,23,42,0.82);
            border-color: rgba(51,65,85,0.9);
            box-shadow: 0 18px 48px rgba(0,0,0,0.32), 0 6px 16px rgba(2,6,23,0.24);
        }

        html.dark .feature-pill {
            background: rgba(15,23,42,0.7);
            border-color: rgba(51,65,85,0.88);
            color: #cbd5e1;
        }

        html.dark .feature-pill:hover {
            background: rgba(16,185,129,0.14);
            border-color: rgba(16,185,129,0.3);
            color: #a7f3d0;
        }

        html.dark .welcome-header {
            background: rgba(2,6,23,0.78) !important;
            border-bottom-color: rgba(51,65,85,0.88) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2) !important;
        }

        html.dark .welcome-footer {
            background: rgba(2,6,23,0.62) !important;
            border-top-color: rgba(51,65,85,0.82) !important;
        }
    </style>
@endpush

<x-guest-layout>
    <div class="min-h-screen flex flex-col hero-bg">
        <div class="grid-pattern"></div>
        <div class="hero-noise"></div>

        {{-- 顶部导航 --}}
            <header class="relative z-20 w-full welcome-header" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid rgba(16,185,129,0.1); box-shadow: 0 1px 12px rgba(0,0,0,0.04);">
            <div class="container mx-auto px-5 sm:px-10 2xl:px-60 h-16 flex justify-between items-center">
                {{-- Logo --}}
                <a href="{{ route('/') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #10b981, #0d9488);">
                        <i class="fas fa-feather-alt text-white text-sm"></i>
                    </div>
                    <span class="font-bold text-lg truncate max-w-[180px]" style="background: linear-gradient(135deg, #059669, #0d9488); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                </a>

                {{-- 右侧按钮 --}}
                <div class="flex items-center gap-3">
                    @includeWhen($_is_notice, 'layouts.notice')
                    @includeWhen($_group->strategies->isNotEmpty(), 'layouts.strategies')

                    @if(Auth::check())
                        @include('layouts.user-nav')
                    @else
                        <a href="{{ route('login') }}"
                            class="nav-link-hover text-slate-600 text-sm font-medium px-4 py-2 rounded-lg transition-all">
                            登录
                        </a>
                        @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableRegistration))
                        <a href="{{ route('register') }}"
                            class="text-white text-sm font-semibold px-5 py-2 rounded-lg transition-all duration-200 hover:-translate-y-0.5"
                            style="background: linear-gradient(135deg, #10b981, #0d9488); box-shadow: 0 4px 14px rgba(16,185,129,0.35);">
                            注册
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </header>

        {{-- 主内容区 --}}
        <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-5 py-16">
            {{-- Hero文字 --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-medium mb-6" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); color: #059669;">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    个人自用 &middot; 安全可靠的图片存储
                </div>
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #10b981, #0d9488); box-shadow: 0 6px 20px rgba(16,185,129,0.3);">
                        <i class="fas fa-feather-alt text-white text-lg"></i>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold" style="background: linear-gradient(135deg, #059669, #0d9488); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</h1>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-700 mb-3">
                    上传、管理、<span style="background: linear-gradient(135deg, #059669, #0d9488); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">分享您的图片</span>
                </p>
                <p class="text-slate-500 text-sm max-w-lg mx-auto leading-relaxed">
                    本站为个人自用图床，图片安全存储于私有服务器。支持多种储存策略与相册管理，快速生成分享链接。
                </p>
            </div>

            {{-- 上传组件容器 --}}
            <div class="w-full max-w-3xl upload-section p-6 sm:p-8">
                <x-upload/>
            </div>

            {{-- 特性标签 --}}
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                @foreach([['fas fa-shield-alt', '安全加密'], ['fas fa-bolt', '极速上传'], ['fas fa-share-alt', '快速分享'], ['fas fa-hdd', '多种储存'], ['fas fa-images', '相册管理']] as $feature)
                <div class="feature-pill flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-all duration-200 cursor-default">
                    <i class="{{ $feature[0] }} text-xs text-emerald-500"></i>
                    <span>{{ $feature[1] }}</span>
                </div>
                @endforeach
            </div>
        </main>

        {{-- 底部 --}}
        <footer class="relative z-10 py-4 welcome-footer" style="background: rgba(255,255,255,0.6); border-top: 1px solid rgba(16,185,129,0.08);">
            <p class="text-center text-slate-400 text-xs">
                初始项目:&nbsp;<a href="https://github.com/lsky-org/lsky-pro" target="_blank" rel="noreferrer" class="hover:text-emerald-500 transition-colors">兰空图床</a>
                &nbsp;|&nbsp;
                UI设计:&nbsp;<a href="https://github.com/willow-god/LSKY-Pro-LiuShen" target="_blank" rel="noreferrer" class="hover:text-emerald-500 transition-colors">清羽飞扬</a>
            </p>
            <p class="text-center text-slate-400 text-xs mt-1">
                &copy; {{ date('Y') }} {{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}. All rights reserved.
                @if(\App\Utils::config(\App\Enums\ConfigKey::IcpNo))
                &nbsp;&middot;&nbsp; <a href="https://beian.miit.gov.cn/" target="_blank" rel="noreferrer" class="hover:text-emerald-500 transition-colors">{{ \App\Utils::config(\App\Enums\ConfigKey::IcpNo) }}</a>
                @endif
            </p>
        </footer>
    </div>

    @include('common.notice')

</x-guest-layout>
