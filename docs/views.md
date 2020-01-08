Views
=====

Here it is the example for your views.

```
@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">{{ __('Posts') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Page</li>
@endpush

@section('title', __('Content'))

@section('content')

    <div id="some-container">Content</div>

@endsection


@push('styles')
    <style>
        .some-class {
            background-color: #000;
        }
    </style>
@endpush

@push('scripts')
    <script>
      (function (d, $) {
        'use strict';
        
        // ...
      })(document, jQuery);
    </script>
@endpush
```

For create/edit forms you may use [html builder](https://github.com/spatie/laravel-html).

```
// view for resource creation

@section('content')

    @include('admin.clients._form', [
        'method' => 'post',
        'route' => route('admin.clients.store'),
        'submit' => [
            'type' => 'success',
            'label' => __('Create'),
        ]
    ])

@endsection
```

```
// _form.blade.php

{{ html()->modelForm($model, $method, $route)->open() }}

<div class="form-group">
    {{ html()->label($model::getAttributeLabel('name'), 'name') }}

    {{ html()->text('name')->class([
        'form-control',
        'is-invalid' => $errors->has('name')
    ]) }}

    @error('name')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}


{{ html()->closeModelForm() }}
```

JS
---

As described above you can [push](https://laravel.com/docs/6.x/blade#stacks)
scripts to [stack](https://laravel.com/docs/6.x/blade#stacks).
This will place your js code to the page bottom.

But if you need your scripts somewhere in the middle of the page body,
e.g. right after scriptable html element, and you need js library (jQuery etc.),
that initializes at the page bottom,
use `deferredCallbacks` object:

```
@section('content')

    <div id="some-container">Content</div>
    
    <scrypt>
      window.deferredCallbacks.myFunction = function (w, d, $) {
        'use strict';
        
        // w = window
        // d = document
        // $ = jQuery
        
        // ...
      };
    </scrypt>

@endsection
```

Installed libraries:

- [jQuery](https://jquery.com/)
- [Bootbox](http://bootboxjs.com/)
- [Moment](https://momentjs.com/) - (available in global `window` object).
- [DateRangePicker](https://www.daterangepicker.com/) - use `js-datepicker` class to initialize popup with calendar.
- [Select2](https://select2.org/) - use `js-select2` class to quickly initialize \<select\> element.
- [SunEditor](https://github.com/JiHong88/SunEditor) - use `js-suneditor` and `js-suneditor-full` to convert textarea into wysiwyg editor.
- [html5sortable](https://github.com/lukasoppermann/html5sortable) - used in `@modelSort` component.
(available in global `window` object).

Links for POST/DELETE requests
------------------------------

If you need link, but request by this link must be POST, PUT/PATCH or DELETE,
use `data-method` attribute.

```html
<a href="/logout" data-method="post">{{ __('Logout') }}</a>
```

Additionally you can make confirm dialog before request.

```html
<a href="/delete" data-method="delete" data-confirm="{{ __('Are you sure?') }}">
    {{ __('Delete') }}
</a>
```

Flashes
-------

[Flash](https://laravel.com/docs/6.x/session#flash-data) messages with keys

- success
- error
- info
- default

automatically will be converted to [Toasts](https://getbootstrap.com/docs/4.3/components/toasts/).

```php
$request->session()->flash('success', 'Task was successful!');
```

You can use `@toast` component separately.

```
@push('toasts')
    @toast(['type' => 'info', 'icon' => 'thumbs-up', 'title' => 'Process'])
    {{ --('Done') }} !!
    @endtoast
@endpush
```

Navigation
----------

`vendor:publish` command will publish some partial views
to `resources/views/vendor/admin` that you can modify.

Files for menu customization are

- [nav/side.blade.php](../resources/views/nav/side.blade.php)
- [nav/top.blade.php](../resources/views/nav/top.blade.php)

---

[Table of contents](./index.md)
