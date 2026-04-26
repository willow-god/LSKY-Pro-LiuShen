<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApiController extends Controller
{
    use InteractsWithTokenAbilities;

    public function index(): View
    {
        return view('common.api', [
            'tokenAbilityGroups' => $this->tokenAbilityGroups(),
        ]);
    }

    public function tokens(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $tokens = $user->tokens()
            ->select(['id', 'name', 'abilities', 'last_used_at', 'created_at'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($token) {
                $token->ability_groups = $this->tokenAbilitiesForDisplay($token->abilities);
                $token->ability_names = collect($token->ability_groups)
                    ->flatMap(fn (array $group) => collect($group['abilities'])->pluck('label'))
                    ->values()
                    ->all();

                return $token;
            });

        return view('common.tokens', [
            'tokens' => $tokens,
            'tokenAbilityGroups' => $this->tokenAbilityGroups(),
            'tokenAbilitiesMap' => $tokens->mapWithKeys(fn ($token) => [(string) $token->id => $token->ability_groups])->all(),
            'currentUserEmail' => $user->email,
            'allTokenAbilities' => $this->allTokenAbilities(),
        ]);
    }
}
