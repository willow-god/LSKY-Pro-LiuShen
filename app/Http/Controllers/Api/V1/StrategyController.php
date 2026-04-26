<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Strategy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StrategyController extends Controller
{
    use InteractsWithTokenAbilities;

    public function index(Request $request): Response
    {
        $this->authenticateOptionalBearerToken($request);

        if ($response = $this->ensureTokenAbility('strategies.read')) {
            return $response;
        }

        /** @var Group $group */
        $group = Auth::check() ? Auth::user()->group : Group::query()->where('is_guest', true)->first();
        $strategies = $group->strategies()->get()->each(fn (Strategy $strategy) => $strategy->setVisible(['id', 'name']));
        return $this->success('success', compact('strategies'));
    }
}
