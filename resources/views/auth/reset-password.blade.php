<x-guest-layout>
    <x-auth-card>
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-slate-800">重置密码</h3>
            <p class="text-slate-500 text-sm mt-1">设置您的新密码</p>
        </div>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">邮箱地址</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-envelope text-sm"></i></span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">新密码</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                    <input id="password" type="password" name="password" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="至少 8 位字符">
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">确认新密码</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400 focus:bg-white transition-all duration-200"
                        placeholder="再次输入新密码">
                </div>
            </div>

            <div class="pt-1">
                <button type="submit"
                    class="w-full py-2.5 px-4 rounded-lg text-white text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
                    style="background: linear-gradient(135deg, #059669, #0d9488); box-shadow: 0 4px 14px rgba(16,185,129,0.35);">
                    <i class="fas fa-key mr-2"></i>{{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
