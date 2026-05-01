<?php

namespace App\Services\OAuth;

use App\Enums\ConfigKey;
use App\Utils;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthService
{
    public const PROVIDER = 'custom-oauth';
    private const STATE_KEY = 'oauth.state';
    private const CODE_VERIFIER_KEY = 'oauth.code_verifier';

    public function isEnabled(): bool
    {
        return (bool) Utils::config(ConfigKey::OauthEnable, false);
    }

    public function canRegister(): bool
    {
        return (bool) Utils::config(ConfigKey::OauthAllowRegister, false);
    }

    public function usesPkce(): bool
    {
        return (bool) Utils::config(ConfigKey::OauthPkceEnable, true);
    }

    public function providerName(): string
    {
        return (string) Utils::config(ConfigKey::OauthProviderName, 'OAuth 2.0');
    }

    public function buildAuthorizationUrl(string $intent): string
    {
        $state = Str::random(40);
        session()->put(self::STATE_KEY, [
            'value' => $state,
            'intent' => $intent,
        ]);

        $query = [
            'response_type' => 'code',
            'client_id' => $this->clientId(),
            'redirect_uri' => $this->redirectUri($intent),
            'scope' => $this->scope(),
            'state' => $state,
        ];

        if ($this->usesPkce()) {
            $verifier = Str::random(96);
            session()->put(self::CODE_VERIFIER_KEY, $verifier);
            $query['code_challenge'] = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
            $query['code_challenge_method'] = 'S256';
        }

        return $this->authorizeUrl().'?'.http_build_query(array_filter($query, fn ($value) => $value !== ''));
    }

    public function validateState(?string $state, string $intent): void
    {
        $stored = session()->pull(self::STATE_KEY);

        if (! is_string($state) || $state === '') {
            throw new OAuthException('OAuth 状态校验失败，请重试');
        }

        if (! is_array($stored)
            || ! isset($stored['value'], $stored['intent'])
            || ! is_string($stored['value'])
            || ! hash_equals($stored['value'], $state)
            || ($stored['intent'] ?? null) !== $intent) {
            throw new OAuthException('OAuth 状态校验失败，请重试');
        }
    }

    public function fetchAccessToken(string $code, string $intent): array
    {
        if ($code === '') {
            throw new OAuthException('OAuth 授权码缺失，请重试');
        }

        $payload = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->clientId(),
            'client_secret' => $this->clientSecret(),
            'redirect_uri' => $this->redirectUri($intent),
        ];

        if ($this->usesPkce()) {
            $verifier = session()->pull(self::CODE_VERIFIER_KEY);

            if (! is_string($verifier) || $verifier === '') {
                throw new OAuthException('OAuth PKCE 校验失败，请重新发起授权');
            }

            $payload['code_verifier'] = $verifier;
        }

        $response = Http::asForm()
            ->acceptJson()
            ->timeout(10)
            ->post($this->tokenUrl(), $payload);

        return $this->decodeJson($response, 'OAuth Token 获取失败');
    }

    public function fetchUserProfile(string $accessToken): array
    {
        $response = Http::acceptJson()
            ->timeout(10)
            ->withToken($accessToken)
            ->get($this->userinfoUrl());

        return $this->decodeJson($response, 'OAuth 用户信息获取失败');
    }

    public function normalizeProfile(array $profile): array
    {
        $userId = Arr::get($profile, $this->userIdField());

        if (! is_scalar($userId) || $userId === '') {
            throw new OAuthException('OAuth 用户信息中缺少唯一标识字段');
        }

        $name = Arr::get($profile, $this->userNameField());
        $email = Arr::get($profile, $this->userEmailField());

        return [
            'provider' => self::PROVIDER,
            'provider_user_id' => (string) $userId,
            'provider_user_name' => is_scalar($name) ? trim((string) $name) : '',
            'provider_user_email' => is_scalar($email) ? trim((string) $email) : '',
            'raw_profile' => $profile,
        ];
    }

    public function syncOauthAccountData(array $tokenPayload, array $profile): array
    {
        $scope = $tokenPayload['scope'] ?? $this->scope();

        return [
            'provider_user_name' => $profile['provider_user_name'] ?: null,
            'provider_user_email' => $profile['provider_user_email'] ?: null,
            'access_token' => isset($tokenPayload['access_token']) ? encrypt((string) $tokenPayload['access_token']) : null,
            'refresh_token' => isset($tokenPayload['refresh_token']) ? encrypt((string) $tokenPayload['refresh_token']) : null,
            'token_expires_at' => isset($tokenPayload['expires_in']) ? now()->addSeconds((int) $tokenPayload['expires_in']) : null,
            'scopes' => collect(preg_split('/[\s,]+/', (string) $scope, -1, PREG_SPLIT_NO_EMPTY))->values(),
            'raw_profile' => $profile['raw_profile'],
        ];
    }

    public function redirectUri(string $intent): string
    {
        return $intent === 'bind'
            ? route('oauth.bind.callback')
            : route('oauth.callback');
    }

    public function userIdField(): string
    {
        return trim((string) Utils::config(ConfigKey::OauthUserIdField, 'sub')) ?: 'sub';
    }

    public function userNameField(): string
    {
        return trim((string) Utils::config(ConfigKey::OauthUserNameField, 'name')) ?: 'name';
    }

    public function userEmailField(): string
    {
        return trim((string) Utils::config(ConfigKey::OauthUserEmailField, 'email')) ?: 'email';
    }

    public function scope(): string
    {
        return trim((string) Utils::config(ConfigKey::OauthScope, 'openid profile email')) ?: 'openid profile email';
    }

    public function authorizeUrl(): string
    {
        return $this->requireHttpsUrl((string) Utils::config(ConfigKey::OauthAuthorizeUrl, ''), 'OAuth 授权地址未配置或格式不正确');
    }

    public function tokenUrl(): string
    {
        return $this->requireHttpsUrl((string) Utils::config(ConfigKey::OauthTokenUrl, ''), 'OAuth Token 地址未配置或格式不正确');
    }

    public function userinfoUrl(): string
    {
        return $this->requireHttpsUrl((string) Utils::config(ConfigKey::OauthUserinfoUrl, ''), 'OAuth 用户信息地址未配置或格式不正确');
    }

    public function clientId(): string
    {
        $clientId = trim((string) Utils::config(ConfigKey::OauthClientId, ''));

        if ($clientId === '') {
            throw new OAuthException('OAuth Client ID 未配置');
        }

        return $clientId;
    }

    public function clientSecret(): string
    {
        $clientSecret = trim((string) Utils::config(ConfigKey::OauthClientSecret, ''));

        if ($clientSecret === '') {
            throw new OAuthException('OAuth Client Secret 未配置');
        }

        return $clientSecret;
    }

    private function decodeJson(Response $response, string $message): array
    {
        if (! $response->successful()) {
            throw new OAuthException($message);
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new OAuthException($message);
        }

        if (! empty($data['error'])) {
            throw new OAuthException(is_scalar($data['error_description'] ?? null)
                ? (string) $data['error_description']
                : $message);
        }

        return $data;
    }

    private function requireHttpsUrl(string $value, string $message): string
    {
        $value = trim($value);

        if ($value === '' || ! filter_var($value, FILTER_VALIDATE_URL)) {
            throw new OAuthException($message);
        }

        if (parse_url($value, PHP_URL_SCHEME) !== 'https') {
            throw new OAuthException($message);
        }

        return $value;
    }
}
