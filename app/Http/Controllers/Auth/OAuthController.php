<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ConfigKey;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\OAuth\OAuthException;
use App\Services\OAuth\OAuthService;
use App\Utils;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(OAuthService $service): RedirectResponse
    {
        if (! Schema::hasTable('oauth_accounts')) {
            return redirect()->route('login')->withErrors(['email' => 'OAuth 数据表不存在，请先执行数据库迁移']);
        }

        try {
            return redirect()->away($service->buildAuthorizationUrl('login'));
        } catch (OAuthException $e) {
            return redirect()->route('login')->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function callback(Request $request, OAuthService $service): RedirectResponse
    {
        try {
            $service->validateState($request->query('state'), 'login');
            $tokenPayload = $service->fetchAccessToken((string) $request->query('code'), 'login');
            $profile = $service->normalizeProfile($service->fetchUserProfile((string) ($tokenPayload['access_token'] ?? '')));
        } catch (OAuthException $e) {
            return redirect()->route('login')->withErrors(['email' => $e->getMessage()]);
        }

        $oauthAccount = OAuthAccount::query()
            ->where('provider', $profile['provider'])
            ->where('provider_user_id', $profile['provider_user_id'])
            ->first();

        if ($oauthAccount) {
            $linkedUser = $oauthAccount->user;

            if (! $linkedUser || $linkedUser->status !== UserStatus::Normal) {
                return redirect()->route('login')->withErrors(['email' => '该账号状态异常，暂时无法通过 OAuth 登录']);
            }

            $oauthAccount->forceFill($service->syncOauthAccountData($tokenPayload, $profile))->save();
            Auth::login($linkedUser);
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        if (! $service->canRegister()) {
            return redirect()->route('login')->withErrors([
                'email' => '当前站点不允许通过 OAuth 自动注册，请先使用邮箱注册并在个人设置中绑定账号。',
            ]);
        }

        $email = trim((string) $profile['provider_user_email']);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('login')->withErrors([
                'email' => 'OAuth 用户信息中的邮箱无效，无法自动注册，请先使用邮箱注册后再绑定。',
            ]);
        }

        if (User::query()->where('email', $email)->exists()) {
            return redirect()->route('login')->withErrors([
                'email' => '该邮箱已存在，请先登录现有账号并在个人设置中绑定 OAuth 账号。',
            ]);
        }

        $name = trim((string) $profile['provider_user_name']);
        $user = DB::transaction(function () use ($request, $profile, $tokenPayload, $service, $email, $name) {
            $user = User::create([
                'name' => $name !== '' ? Str::limit($name, 20, '') : 'OAuth 用户',
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'registered_ip' => $request->ip(),
            ]);

            $user->oauthAccounts()->create([
                'provider' => $profile['provider'],
                'provider_user_id' => $profile['provider_user_id'],
                ...$service->syncOauthAccountData($tokenPayload, $profile),
            ]);

            if (Utils::config(ConfigKey::IsUserNeedVerify)) {
                event(new Registered($user));
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function bindRedirect(OAuthService $service): RedirectResponse
    {
        if (! $service->isEnabled()) {
            return redirect()->route('settings')->withErrors(['oauth' => '站点管理员关闭了 OAuth 登录功能']);
        }

        if (! Schema::hasTable('oauth_accounts')) {
            return redirect()->route('settings')->withErrors(['oauth' => 'OAuth 数据表不存在，请先执行数据库迁移']);
        }

        try {
            return redirect()->away($service->buildAuthorizationUrl('bind'));
        } catch (OAuthException $e) {
            return redirect()->route('settings')->withErrors(['oauth' => $e->getMessage()]);
        }
    }

    public function bindCallback(Request $request, OAuthService $service): RedirectResponse
    {
        if (! $service->isEnabled()) {
            return redirect()->route('settings')->withErrors(['oauth' => '站点管理员关闭了 OAuth 登录功能']);
        }

        /** @var User $user */
        $user = Auth::user();

        try {
            $service->validateState($request->query('state'), 'bind');
            $tokenPayload = $service->fetchAccessToken((string) $request->query('code'), 'bind');
            $profile = $service->normalizeProfile($service->fetchUserProfile((string) ($tokenPayload['access_token'] ?? '')));
        } catch (OAuthException $e) {
            return redirect()->route('settings')->withErrors(['oauth' => $e->getMessage()]);
        }

        $exists = OAuthAccount::query()
            ->where('provider', $profile['provider'])
            ->where('provider_user_id', $profile['provider_user_id'])
            ->where('user_id', '!=', $user->id)
            ->exists();

        if ($exists) {
            return redirect()->route('settings')->withErrors(['oauth' => '该 OAuth 账号已绑定到其他用户，无法重复绑定']);
        }

        $user->oauthAccounts()->updateOrCreate(
            ['provider' => $profile['provider']],
            [
                'provider_user_id' => $profile['provider_user_id'],
                ...$service->syncOauthAccountData($tokenPayload, $profile),
            ]
        );

        return redirect()->route('settings')->with('oauth_status', 'OAuth 账号绑定成功');
    }

    public function unbind(Request $request, OAuthAccount $oauthAccount): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'password' => 'required|string',
        ]);

        if ($oauthAccount->user_id !== $user->id) {
            return $this->fail('没有找到该绑定记录');
        }

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            return $this->fail('密码错误，请重新输入');
        }

        $oauthAccount->delete();

        return $this->success('解绑成功');
    }
}
