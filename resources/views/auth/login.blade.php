<x-guest-layout>
    <x-auth-card>
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium mb-4" style="background: rgba(16,185,129,0.08); color: #059669; border: 1px solid rgba(16,185,129,0.18);">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                欢迎回来
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">登录账号</h3>
            <p class="text-slate-500 text-sm mt-1.5">请输入您的账号和密码继续</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">邮箱地址</label>
                <div class="relative group">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors duration-200"><i class="fas fa-envelope text-sm"></i></span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="your@email.com">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">密码</label>
                <div class="relative group">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors duration-200"><i class="fas fa-lock text-sm"></i></span>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/80 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                    <input id="remember_me" type="checkbox"
                        class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer"
                        name="remember">
                    <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">记住我</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold transition-colors" href="{{ route('password.request') }}">
                        忘记密码？
                    </a>
                @endif
            </div>

            <!-- Login Button -->
            <div class="pt-1">
                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl text-white text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 4px 16px rgba(16,185,129,0.35);">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>立即登录</span>
                </button>
            </div>

            @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableRegistration))
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                <div class="relative flex justify-center"><span class="px-3 bg-white text-xs text-slate-400">或者</span></div>
            </div>
            <p class="text-center text-sm text-slate-500">
                还没有账号？
                <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors">
                    免费注册 <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </p>
            @endif
        </form>
    </x-auth-card>
</x-guest-layout>
