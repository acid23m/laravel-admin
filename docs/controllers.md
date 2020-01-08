Controllers
===========

Extend your controllers from
[\SP\Admin\Http\Controllers\AdminController](../src/Http/Controllers/AdminController.php).
It will protect your controllers with `admin guard`.

Form requests
-------------

If your model has attributes with boolean values (e.g. "active")
and you uses checkboxes in form for them,
you may discover that this attributes do not present in request data
if checkbox is unchecked.

To fix this issue you can extend your form requests from
[\SP\Admin\Http\Requests\AbstractFormRequest](../src/Http/Requests/AbstractFormRequest.php)
and fill `$from_checkbox` list with attributes.

```php
<?php
declare(strict_types=1);

namespace App\Http\Requests;

use SP\Admin\Http\Requests\AbstractFormRequest;

/**
 * Class ClientRequest.
 *
 * @package App\Http\Requests
 */
final class ClientRequest extends AbstractFormRequest
{
    /**
     * Attributes with boolean values.
     *
     * @var array
     */
    protected array $from_checkbox = [
        'active',
    ];

    /**
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:50',
            'status' => 'nullable|max:20',
            'active' => 'boolean',
        ];
    }
    
}
```

Routes
------

```php
<?php
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
        Route::match(['put', 'patch'], 'posts/{post}/restore', 'Admin\PostController@restore')->name('posts.restore');
        Route::delete('posts/{post}/force-delete', 'Admin\PostController@forceDelete')->name('posts.force-delete');
        Route::match(['put', 'patch'], 'posts/sort', 'Admin\PostController@sort')->name('posts.sort');
        Route::resource('posts', 'Admin\PostController');
    });
```

---

[Table of contents](./index.md)
