<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use InteractsWithTokenAbilities;

    public function index(): Response
    {
        if ($response = $this->ensureTokenAbility('profile.read')) {
            return $response;
        }

        /** @var User $user */
        $user = Auth::user();
        $user->used_capacity = $user->images()->sum('size') + 0;
        $user->setVisible([
            'name', 'avatar', 'email', 'capacity', 'used_capacity', 'url', 'image_num', 'album_num', 'registered_ip'
        ]);
        return $this->success('success', $user);
    }
}
