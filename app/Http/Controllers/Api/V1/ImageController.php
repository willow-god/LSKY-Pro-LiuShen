<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UploadException;
use App\Http\Controllers\Concerns\InteractsWithTokenAbilities;
use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use App\Services\ImageService;
use App\Services\UserService;
use App\Utils;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    use InteractsWithTokenAbilities;

    /**
     * @throws AuthenticationException
     */
    public function upload(Request $request, ImageService $service): Response
    {
        if ($request->hasHeader('Authorization')) {
            $authenticated = $this->authenticateOptionalBearerToken($request);

            if (! $authenticated && ! Auth::check()) {
                throw new AuthenticationException('Authentication failed.');
            }

            if ($response = $this->ensureTokenAbility('images.upload')) {
                return $response;
            }
        }

        try {
            $image = $service->store($request);
        } catch (UploadException $e) {
            return $this->fail($e->getMessage());
        } catch (\Throwable $e) {
            Utils::e($e, 'Api 上传文件时发生异常');
            if (config('app.debug')) {
                return $this->fail($e->getMessage());
            }
            return $this->fail('服务异常，请稍后再试');
        }
        return $this->success('上传成功', $image->setAppends(['pathname', 'links'])->only(
            'key', 'name', 'pathname', 'origin_name', 'size', 'mimetype', 'extension', 'md5', 'sha1', 'links'
        ));
    }

    public function images(Request $request): Response
    {
        if ($response = $this->ensureTokenAbility('images.read')) {
            return $response;
        }

        /** @var User $user */
        $user = Auth::user();

        $images = $user->images()->filter($request)->paginate(40)->withQueryString();
        $images->getCollection()->each(function (Image $image) {
            $image->human_date = $image->created_at->diffForHumans();
            $image->date = $image->created_at->format('Y-m-d H:i:s');
            $image->append(['pathname', 'links'])->setVisible([
                'album', 'key', 'name', 'pathname', 'origin_name', 'size', 'mimetype', 'extension', 'md5', 'sha1',
                'width', 'height', 'links', 'human_date', 'date',
            ]);
        });
        return $this->success('success', $images);
    }

    public function destroy(Request $request): Response
    {
        if ($response = $this->ensureTokenAbility('images.delete')) {
            return $response;
        }

        /** @var User $user */
        $user = Auth::user();
        (new UserService())->deleteImages([$request->route('key')], $user, 'key');
        return $this->success('删除成功');
    }
}
