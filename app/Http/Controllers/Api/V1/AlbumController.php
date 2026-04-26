<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    use InteractsWithTokenAbilities;

    public function index(Request $request): Response
    {
        if ($response = $this->ensureTokenAbility('albums.read')) {
            return $response;
        }

        /** @var User $user */
        $user = Auth::user();
        $albums = $user->albums()->filter($request)->paginate(40);
        $albums->getCollection()->each(function (Album $album) {
            $album->setVisible(['id', 'name', 'intro', 'image_num']);
        });
        return $this->success('success', $albums);
    }

    public function destroy(Request $request): Response
    {
        if ($response = $this->ensureTokenAbility('albums.delete')) {
            return $response;
        }

        /** @var User $user */
        $user = Auth::user();
        $user->albums()->where('id', $request->route('id'))->delete();
        return $this->success('删除成功');
    }
}
