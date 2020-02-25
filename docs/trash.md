Trash bin
=========

Any soft-deleted resource can be shown in Trash bin.

First of all create *soft-delete* functionality for model.

- add `deleted_at` field to the table;

```php
$table->softDeletes();
```

- use `SoftDeletes` trait;
- show deleted models;

```php
// \App\Post

/**
 * Retrieve the model for a bound value.
 *
 * @param mixed $value
 * @return \Illuminate\Database\Eloquent\Model|null
 */
public function resolveRouteBinding($value)
{
    return $this->where('id', $value)->withTrashed()->first() ?? abort(404);
}
```

- add `restore` and `forceDelete` actions to the controller;

```php
// \App\Http\Controllers\Admin\PostController

/**
 * Restores the specified resource from storage.
 *
 * @param Post $post
 * @return RedirectResponse
 */
public function restore(Post $post): RedirectResponse
{
    $post->restore();

    return redirect()
        ->route('admin.posts.show', $post)
        ->with('success', trans('The Record has been restored.'));
}

/**
 * Permanently removes the specified resource from storage.
 *
 * @param Post $post
 * @return RedirectResponse
 */
public function forceDelete(Post $post): RedirectResponse
{
    $post->forceDelete();

    return redirect()
        ->route('admin.posts.index')
        ->with('success', trans('The Record has been deleted.'));
}
```

- add routes;

```php
Route::match(['put', 'patch'], 'admin/posts/{post}/restore', 'Admin\PostController@restore')->name('posts.restore');
Route::delete('admin/posts/{post}/force-delete', 'Admin\PostController@forceDelete')->name('posts.force-delete');
Route::resource('admin/posts', 'Admin\PostController');
```

Next create file with configuration for the trash bin scanner.

```php
return [
    Post::class => [
        'group_name' => 'Posts', // or 'group_name' => fn() => trans('Posts')
        'label' => fn (Post $model): string => $model->title,
        'links' => [
            'view' => fn (Post $model): string => route('admin.posts.show', $model),
            'restore' => fn (Post $model): string => route('admin.posts.restore', $model),
            'delete' => fn (Post $model): string => route('admin.posts.force-delete', $model),
        ],
    ],
];
```

Then go to `config/admin.php` and fill `trash_bin` section.

```php
'trash_bin' => dirname(__DIR__) . '/app/trash_bin.php',
```

---

[Table of contents](./index.md)
