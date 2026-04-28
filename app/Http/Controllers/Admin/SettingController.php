<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConfigKey;
use App\Http\Controllers\Controller;
use App\Mail\Test;
use App\Models\Config;
use App\Services\UpgradeService;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $configs = Utils::config();
        return view('admin.setting.index', compact('configs'));
    }

    public function customCode(Request $request): Response
    {
        $css = Utils::config(ConfigKey::CustomCss, '');
        $js = Utils::config(ConfigKey::CustomJs, '');

        $css = is_string($css) ? $css : '';
        $js = is_string($js) ? $js : '';
        $type = $request->query('type');

        if ($type === 'css') {
            return $this->success('success', [
                'css' => $css,
                'css_hash' => md5($css),
            ]);
        }

        if ($type === 'js') {
            return $this->success('success', [
                'js' => $js,
                'js_hash' => md5($js),
            ]);
        }

        return $this->success('success', [
            'css' => $css,
            'css_hash' => md5($css),
            'js' => $js,
            'js_hash' => md5($js),
        ]);
    }


    public function save(Request $request): Response
    {
        try {
            foreach ($request->except(['_token', '_method']) as $key => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                } elseif (is_null($value)) {
                    $value = '';
                } elseif (is_bool($value) || is_int($value) || is_float($value)) {
                    $value = (string) $value;
                }

                $config = Config::query()->firstOrNew(['name' => $key]);
                $config->value = $value;
                $config->save();
            }
            Cache::flush();
        } catch (\Throwable $e) {
            Utils::e($e, '保存系统设置时出现异常');
            return $this->fail($e->getMessage());
        }

        return $this->success('保存成功');
    }

    public function mailTest(Request $request): Response
    {
        try {
            Mail::to($request->post('email'))->send(new Test());
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
        return $this->success('发送成功');
    }

    public function checkUpdate(): Response
    {
        $version = Utils::config(ConfigKey::AppVersion);
        $service = new UpgradeService($version);
        try {
            $data = [
                'is_update' => $service->check(),
            ];
            if ($data['is_update']) {
                $data['version'] = $service->getVersions()->first();
            }
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }

        return $this->success('success', $data);
    }

    public function upgrade()
    {
        ignore_user_abort(true);
        set_time_limit(0);

        $version = Utils::config(ConfigKey::AppVersion);
        $service = new UpgradeService($version);
        $this->success()->send();
        $service->upgrade();
        flush();
    }

    public function upgradeProgress(): Response
    {
        return $this->success('success', Cache::get('upgrade_progress'));
    }
}
