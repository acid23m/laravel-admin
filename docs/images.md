Images
======

Basically you need to do some work with uploaded images:
change format, resize, make watermark, optimize for web and so on.

There are some helpful tools for it.

Optimizations
-------------

Optimize pictures to decrease file weight.
You can do it

- without loosing quality
- with loosing quality

depend on site needs and content.

[spatie/laravel-image-optimizer](https://github.com/spatie/laravel-image-optimizer)
package do this work. Read the documentation to configure tool or
stay it default.

[Additional software](https://github.com/spatie/image-optimizer#optimization-tools)
must be installed on a server for the package.

```php
<?php
declare(strict_types=1);

namespace App\Observers;

use App\Post;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Spatie\ImageOptimizer\OptimizerChain;

final class PostObserver
{
    private Request $request;
    private Filesystem $filesystem;
    private OptimizerChain $optimizer;

    /**
     * PostObserver constructor.
     *
     * @param Request $request
     * @param FilesystemFactory $f_factory
     * @param OptimizerChain $optimizer
     */
    public function __construct(Request $request, FilesystemFactory $f_factory, OptimizerChain $optimizer)
    {
        $this->request = $request;
        $this->filesystem = $f_factory->disk('public');
        $this->optimizer = $optimizer;
    }
    
    /**
     * Handle the post "creating" event.
     *
     * @param Post $post
     */
    public function creating(Post $post): void
    {
        // saves image
        $image = $this->request->file('image');
        if ($image !== null) {
            $post->image = $this->filesystem->put('posts', $image);
            // optimizes image
            $this->optimizer->optimize(
                $this->filesystem->path($post->image)
            );
        }
    }
    
    /**
     * Handle the post "updating" event.
     *
     * @param Post $post
     */
    public function updating(Post $post): void
    {
        // saves image
        $image = $this->request->file('image');
        if ($image !== null) {
            $this->filesystem->delete(
                $post->getOriginal('image')
            );
            $post->image = $this->filesystem->put('posts', $image);
            // optimizes image
            $this->optimizer->optimize(
                $this->filesystem->path($post->image)
            );
        }
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param Post $post
     */
    public function forceDeleted(Post $post): void
    {
        // deletes image
        $this->filesystem->delete($post->image);
    }
    
}
```

It is highly not recommended to put all hundreds/thousands files in one folder.
Many files in one directory affect performance of a server filesystem.

Use sub-folders: `public/posts/sr/ei/Rt5cHI10w.jpeg`.

```php
/**
 * Relative path to image in public disk.
 *
 * @param string $base_dir
 * @return string
 */
private function relativeImagePath(string $base_dir): string
{
    return \trim($base_dir, '/')
        . DIRECTORY_SEPARATOR . \strtolower(Str::random(2))
        . DIRECTORY_SEPARATOR . \strtolower(Str::random(2));
}
```

Manipulations
-------------

Use [spatie/laravel-glide](https://github.com/spatie/laravel-glide)
package to convert images. See the documentation for usage.

```php
GlideImage::create($path_to_image)
	->modify(['w'=> 50, 'filt'=>'greyscale'])
	->save($path_to_where_to_save_the_manipulated_image);
```

Changes on-the-fly
------------------

For example you have one api server and several web clients (mobile, desktop...).
Probably you need to store original uploaded picture and resize it on-the-fly
depending on client's design.

[Glide](https://glide.thephpleague.com/) library does HTTP based image manipulations.

1. Define settings in `config/admin.php`.

```php
'image_resizer' => [
    'base_url' => 'img',
    'source_disk' => 'public',
    'cache_disk' => 'public',
],
```

So URLs for images will be like this:
`https://mysite.com/img/avatars/et6FDqO17.png?w=150`.

2. Create url with help function `image_glide_url`

```php
// ex.: $model->image = 'avatars/et6FDqO17.png'

$avatar_url = image_glide_url($model->image, [
    'w' => 150,
]);
```

---

[Table of contents](./index.md)
