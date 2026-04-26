<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApiController extends Controller
{
    public function index(): View
    {
        return view('common.api');
    }

    public function tokens(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $tokens = $user->tokens()
            ->select(['id', 'name', 'last_used_at', 'created_at'])
            ->orderByDesc('created_at')
            ->get();

        return view('common.tokens', compact('tokens'));
    }
}
