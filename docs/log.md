Log file
========

Superusers (developers) can see errors on the dashboard.

Add new channel at `config/logging.php` with `single` driver
and custom formatter.

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single_2'],
        'ignore_exceptions' => false,
    ],

    'single_2' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.tbl.log'),
        'level' => 'debug',
        'tap' => [\SP\Admin\Log\LineFormatter::class],
    ],
],
```

Then define that channel in `config/admin.php`.

```php
'log_channel' => 'single_2',
```

Done!

---

[Table of contents](./index.md)
