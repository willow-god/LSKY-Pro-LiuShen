<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    use InteractsWithTokenAbilities;

    /**
     * 获取当前用户的 Token 列表
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $tokens = $user->tokens()
            ->select(['id', 'name', 'abilities', 'last_used_at', 'created_at'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities ?: $this->allTokenAbilities(),
                    'ability_groups' => $this->tokenAbilitiesForDisplay($token->abilities),
                    'last_used_at' => $token->last_used_at?->format('Y-m-d H:i:s'),
                    'created_at' => $token->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return $this->success('success', compact('tokens'));
    }

    /**
     * 创建 Token（API 方式，需验证密码）
     */
    public function store(Request $request): Response
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'abilities' => 'nullable|array',
                'abilities.*' => ['string', Rule::in($this->allTokenAbilities())],
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User|null $user */
        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->fail('The email address or password is incorrect.');
        }

        $abilities = $this->normalizedRequestedAbilities($request->input('abilities'));
        $token = $user->createToken($user->email, $abilities)->plainTextToken;

        return $this->success('success', [
            'token' => $token,
            'abilities' => $abilities,
            'ability_groups' => $this->tokenAbilitiesForDisplay($abilities),
        ]);
    }

    /**
     * Web 端创建 Token（已登录用户，需验证当前密码）
     */
    public function createWebToken(Request $request): Response
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:50',
                'password' => 'required|string',
                'abilities' => 'nullable|array',
                'abilities.*' => ['string', Rule::in($this->allTokenAbilities())],
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->fail('密码错误，请重新输入');
        }

        $name = $request->input('name') ?: $user->email;
        $abilities = $this->normalizedRequestedAbilities($request->input('abilities'));
        $token = $user->createToken($name, $abilities)->plainTextToken;

        return $this->success('创建成功', [
            'token' => $token,
            'abilities' => $abilities,
            'ability_groups' => $this->tokenAbilitiesForDisplay($abilities),
        ]);
    }

    /**
     * 修改 Token 名称（仅允许修改 name 字段）
     */
    public function update(Request $request, int $id): Response
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->tokens()->find($id);

        if (! $token) {
            return $this->fail('Token 不存在');
        }

        $token->forceFill([
            'name' => $request->input('name'),
        ])->save();

        return $this->success('修改成功');
    }

    /**
     * 删除单个 Token（严格校验所有权，需验证当前密码）
     */
    public function destroy(Request $request, int $id): Response
    {
        try {
            $request->validate([
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return $this->fail($e->validator->errors()->first());
        }

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->fail('密码错误，请重新输入');
        }

        $token = $user->tokens()->find($id);

        if (! $token) {
            return $this->fail('Token 不存在');
        }

        $token->delete();

        return $this->success('删除成功');
    }

    /**
     * 清空当前用户的所有 Token
     */
    public function clear(): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success();
    }
}
