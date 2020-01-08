Ordering
========

Sometimes you want to customize order of the resources in the collection.
In this case you need complex solution.

Thirst of all it must be a field in a table to store position number.

```php
// migration

Schema::create('posts', static function (Blueprint $table) {
    $table->unsignedInteger('id')->autoIncrement();
    $table->string('title');
    $table->date('pub_date');
    $table->boolean('active')->default(true);
    $table->string('slug')->unique();
    $table->unsignedInteger('position')->default(1); // ordering resource
    $table->timestamps();
    $table->softDeletes();
});
```

Then add [ModelOrder](../src/Traits/ModelOrder.php) trait to the model.

```php
<?php
declare(strict_types=1);

namespace App;

use SP\Admin\Models\Model;
use SP\Admin\Traits\ModelOrder;

class Post extends Model
{
    use ModelOrder;
    
    public function orderable(): string
    {
        // change field name
        // by default it is "position"
        return 'position';
    }
    
    public function orderGroups(): array
    {
        // grouping attributes
        // if they exists
        return ['parent_id', 'type'];
    }
    
}
```

Define observer to auto-reorder models on create and delete events.

```php
<?php
declare(strict_types=1);

namespace App\Observers;

use App\Post;

/**
 * Class PostObserver.
 *
 * @package App\Observers
 */
final class PostObserver
{
    /**
     * Handle the post "creating" event.
     *
     * @param Post $post
     */
    public function creating(Post $post): void
    {
        $post->lastPosition(false);
    }

    /**
     * Handle the post "force deleted" event.
     *
     * @param Post $post
     */
    public function forceDeleted(Post $post)
    {
        $post->removeAndReorder();
    }

}
```

Create handler to save changes with positions.

```php
// routes/web.php

use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;
use SP\Admin\Http\Middleware\Locale;

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'web',
        Locale::class,
        AuthenticateSession::class,
    ])->group(static function () {
        // posts
        Route::match(['put', 'patch'], 'posts/sort', 'Admin\PostController@sort')->name('posts.sort');
        Route::resource('posts', 'Admin\PostController');
    });
```

```php
<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SP\Admin\Http\Controllers\AdminController;

class PostController extends AdminController
{
    public function sort(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sorted_ids' => 'json',
        ]);

        $ids = json_decode($data['sorted_ids'], true, 512, JSON_THROW_ON_ERROR);

        foreach ($ids as $index => $id) {
            $model = Post::withTrashed()->where('id', $id)->first();
            if ($model !== null) {
                $model->moveToPosition($index + 1); // reorder and save
            }
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', trans('The Records has been sorted.'));
    }
}
```

Finally add sortable grid to the view.

```
// resources/views/admin/posts/index.blade.php

@modelSort(['input_name' => 'sorted_ids', 'grid_columns' => 5])
@each('admin.posts._sort_item', $sorting_models, 'sorting_model')
@endmodelSort
```

Where `input_name` is the *post request field* with ordered list
and `grid_columns` is the number of columns in each grid row.
This settings are optional.

`@each` directive contains the view name.
This view is markup for each grid cell.

```
// resources/views/admin/posts/_sort_item.blade.php

@php $item_class = '' @endphp
@if(!$sorting_model['active']) @php $item_class = 'bg-light border-light text-muted' @endphp @endif
@if($sorting_model['trashed']) @php $item_class = 'bg-light border-danger text-muted' @endphp @endif

<div class="sortable-item" data-id="{{ $sorting_model['id'] }}">
    <div class="card {{ $item_class }}">
        <div class="card-body">
            <h5 class="card-title">{{ $sorting_model['title'] }}</h5>
        </div>
        <div class="card-footer">
            {{ $sorting_model['position'] }}
            @if(!$sorting_model['active']) ({{ __('Not Active') }}) @endif
            @if($sorting_model['trashed']) ({{ __('Deleted') }}) @endif
        </div>
    </div>
</div>
```

Necessarily you need to define container with `sortable-item` class
and `data-id` attribute with the resource ID.

---

[Table of contents](./index.md)
