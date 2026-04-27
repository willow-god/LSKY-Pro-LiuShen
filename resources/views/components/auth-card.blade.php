<div class="min-h-screen flex items-stretch">
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden" style="background: linear-gradient(145deg, #064e3b 0%, #059669 40%, #0d9488 75%, #0891b2 100%);">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-32 -left-32 w-[480px] h-[480px] rounded-full" style="background: radial-gradient(circle, rgba(16,185,129,0.35) 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 right-0 w-[360px] h-[360px] rounded-full" style="background: radial-gradient(circle, rgba(8,145,178,0.25) 0%, transparent 70%);"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full" style="background: radial-gradient(circle, rgba(13,148,136,0.15) 0%, transparent 70%);"></div>
        <div class="absolute top-16 right-24 w-14 h-14 rounded-2xl rotate-12 opacity-20" style="background: rgba(255,255,255,0.3); backdrop-filter: blur(4px);"></div>
        <div class="absolute bottom-32 left-16 w-10 h-10 rounded-xl -rotate-12 opacity-15" style="background: rgba(255,255,255,0.3);"></div>
        <div class="absolute top-1/3 right-16 w-6 h-6 rounded-full opacity-25" style="background: rgba(255,255,255,0.5);"></div>

        <div class="relative z-10 flex flex-col justify-center px-14 xl:px-20 text-white">
            <div class="mb-10 flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg" style="background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.25);">
                    <i class="fas fa-feather-alt text-white text-2xl"></i>
                </div>
                <div>
                    <span class="text-2xl font-extrabold tracking-tight block">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                    <span class="text-white/60 text-xs tracking-widest uppercase">Image Hosting</span>
                </div>
            </div>

            <h2 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-5 tracking-tight">
                轻盈存储<br>
                <span style="background: linear-gradient(135deg, #a7f3d0, #99f6e4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">无限可能</span>
            </h2>
            <p class="text-white/65 text-base leading-relaxed mb-10 max-w-sm">
                安全、快速地托管您的每一张图片，支持多种储存策略与灵活分享。
            </p>

            <div class="flex flex-col gap-3.5">
                @foreach([
                    ['fa-shield-alt', '端到端安全加密，保障数据隐私'],
                    ['fa-bolt', '极速上传，支持 WebP / GIF / RAW 等'],
                    ['fa-share-alt', 'BBCode / Markdown / 直链多格式分享'],
                    ['fa-layer-group', '多储存策略，本地 / OSS / S3 自由选择'],
                ] as $f)
                <div class="flex items-center gap-3.5" style="animation: fadeInLeft 0.5s ease both;">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18);">
                        <i class="fas {{ $f[0] }} text-xs text-white/90"></i>
                    </div>
                    <span class="text-sm text-white/75">{{ $f[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 xl:w-2/5 flex flex-col justify-center items-center px-8 sm:px-12 py-12 relative" style="background: var(--panel-bg-strong);">
        <div class="absolute top-0 right-0 w-48 h-48 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(99,102,241,0.04) 0%, transparent 70%);"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(139,92,246,0.04) 0%, transparent 70%);"></div>

        <div class="w-full max-w-sm relative z-10">
            <div class="flex items-center gap-2.5 mb-8 lg:hidden">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                    <i class="fas fa-feather-alt text-white text-base"></i>
                </div>
                <div>
                    <span class="text-lg font-bold block leading-tight text-[var(--text-primary)]">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                    <span class="text-xs text-[var(--text-muted)]">Image Hosting</span>
                </div>
            </div>
            {{ $slot }}
        </div>
        <p class="mt-10 text-xs text-center relative z-10 text-[var(--text-muted)]">
            初始项目:&nbsp;<a href="https://github.com/lsky-org/lsky-pro" target="_blank" rel="noreferrer" class="hover:text-emerald-400 transition-colors">兰空图床</a>
            &nbsp;|&nbsp;
            UI设计:&nbsp;<a href="https://github.com/willow-god/LSKY-Pro-LiuShen" target="_blank" rel="noreferrer" class="hover:text-emerald-400 transition-colors">清羽飞扬</a>
        </p>
        <p class="mt-1 text-xs text-center relative z-10 text-[var(--text-muted)]">
            &copy; {{ date('Y') }} {{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}
            @if(\App\Utils::config(\App\Enums\ConfigKey::IcpNo))
            &nbsp;&middot;&nbsp; <a href="https://beian.miit.gov.cn/" target="_blank" class="hover:text-emerald-400 transition-colors">{{ \App\Utils::config(\App\Enums\ConfigKey::IcpNo) }}</a>
            @endif
        </p>
    </div>
</div>
