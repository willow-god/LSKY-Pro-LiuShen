<?php

namespace App\Http\Middleware;

use App\Enums\ConfigKey;
use App\Http\Result;
use App\Utils;
use Closure;
use Illuminate\Http\Request;

class CheckIsEnableOAuth
{
    use Result;

    public function handle(Request $request, Closure $next)
    {
        if (! Utils::config(ConfigKey::OauthEnable)) {
            if ($request->expectsJson()) {
                return $this->fail('站点管理员关闭了 OAuth 登录功能')->setStatusCode(403);
            }

            return redirect()->route('login')->withErrors(['email' => '站点管理员关闭了 OAuth 登录功能']);
        }

        return $next($request);
    }
}
