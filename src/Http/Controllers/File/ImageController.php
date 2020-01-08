<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\File;

use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\{SignatureException, SignatureFactory};
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImageController.
 *
 * @package SP\Admin\Http\Controllers
 */
class ImageController extends Controller
{
    /**
     * Manipulates images on-the-fly.
     *
     * @param Request $request
     * @param FilesystemFactory $filesystem
     * @param $path
     * @return Response|null
     */
    public function show(Request $request, FilesystemFactory $filesystem, $path): ?Response
    {
        $query_params = $this->queryParams(
            $request->getRequestUri()
        );
        $base_url = config('admin.image_glide_base_url', 'img');

        try {
            $full_path = "/$base_url/" . \trim($path, '/');
            SignatureFactory::create(config('app.key'))->validateRequest($full_path, $query_params);
        } catch (SignatureException $e) {
            return null;
        }

        $disk = $filesystem->disk('public');

        $server = ServerFactory::create([
            'driver' => \extension_loaded('imagick') ? 'imagick' : 'gd',
            'response' => new LaravelResponseFactory($request),
            'source' => $disk->path(''),
            'cache' => $disk->path(''),
            'cache_path_prefix' => 'cache',
            'base_url' => $base_url,
        ]);

        return $server->getImageResponse($path, $query_params);
    }

    /**
     * Parses query string manually.
     *
     * @param string $uri
     * @return array
     */
    protected function queryParams(string $uri): array
    {
        $u = \parse_url($uri);
        $qs = \explode('&', $u['query']);

        $params = [];
        foreach ($qs as $q) {
            [$key, $value] = \explode('=', $q);
            $params[$key] = $value;
        }

        return $params;
    }

}
