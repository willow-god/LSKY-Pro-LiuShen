<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

trait InteractsWithTokenAbilities
{
    protected function allTokenAbilities(): array
    {
        return collect(config('token_abilities.groups', []))
            ->pluck('abilities')
            ->flatMap(fn (array $abilities) => array_keys($abilities))
            ->values()
            ->all();
    }

    protected function tokenAbilityGroups(): array
    {
        return config('token_abilities.groups', []);
    }

    protected function normalizedRequestedAbilities(?array $abilities): array
    {
        $allowed = $this->allTokenAbilities();
        $selected = collect($abilities ?? [])->filter(fn ($ability) => is_string($ability) && in_array($ability, $allowed, true));

        if ($selected->isEmpty()) {
            return $allowed;
        }

        return $selected->values()->unique()->all();
    }

    protected function tokenAbilitiesForDisplay(?array $abilities): array
    {
        $selected = empty($abilities) || in_array('*', $abilities, true)
            ? $this->allTokenAbilities()
            : collect($abilities)->values()->all();

        return collect($this->tokenAbilityGroups())
            ->map(function (array $group) use ($selected) {
                $abilities = collect($group['abilities'])
                    ->filter(fn ($_label, $ability) => in_array($ability, $selected, true))
                    ->map(fn ($label, $ability) => [
                        'key' => $ability,
                        'label' => $label,
                    ])
                    ->values()
                    ->all();

                return [
                    'key' => $group['key'],
                    'label' => $group['label'],
                    'abilities' => $abilities,
                ];
            })
            ->filter(fn (array $group) => ! empty($group['abilities']))
            ->values()
            ->all();
    }

    protected function currentPersonalAccessToken(): ?PersonalAccessToken
    {
        $user = request()->user();

        if (! $user || ! method_exists($user, 'currentAccessToken')) {
            return null;
        }

        $token = $user->currentAccessToken();

        return $token instanceof PersonalAccessToken ? $token : null;
    }

    protected function requestUsesScopedToken(): bool
    {
        return ! is_null($this->currentPersonalAccessToken());
    }

    protected function authenticateOptionalBearerToken(Request $request): bool
    {
        if (! $request->bearerToken()) {
            return false;
        }

        if ($this->requestUsesScopedToken()) {
            return true;
        }

        $token = PersonalAccessToken::findToken($request->bearerToken());

        if (! $token || ! $token->tokenable || ! method_exists($token->tokenable, 'withAccessToken')) {
            return false;
        }

        $user = $token->tokenable->withAccessToken($token);

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return true;
    }

    protected function tokenHasAbility(string $ability): bool
    {
        $token = $this->currentPersonalAccessToken();

        if (! $token) {
            return true;
        }

        if (empty($token->abilities)) {
            return true;
        }

        if (in_array('*', $token->abilities, true)) {
            return true;
        }

        return in_array($ability, $token->abilities, true);
    }

    protected function ensureTokenAbility(string $ability): ?Response
    {
        if (! $this->requestUsesScopedToken()) {
            return null;
        }

        if ($this->tokenHasAbility($ability)) {
            return null;
        }

        return $this->forbidden();
    }
}
