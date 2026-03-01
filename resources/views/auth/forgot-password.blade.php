<x-guest-layout>
    <x-auth-card>
        <div class="mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-5">
                <i class="fas fa-arrow-left text-xs"></i> 返回登录
            </a>
            <h3 class="text-2xl font-bold text-slate-800">找回密码</h3>
            <p class="text-slate-500 text-sm mt-1">输入注册邮箱，我们将发送密码重置链接</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">邮箱地址</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope text-sm"></i></span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="your@email.com">
                </div>
            </div>

            <button type="submit"
                class="w-full py-2.5 px-4 rounded-lg text-white text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
                style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 4px 14px rgba(16,185,129,0.4);">
                <i class="fas fa-paper-plane mr-2"></i>{{ __('Email Password Reset Link') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>
