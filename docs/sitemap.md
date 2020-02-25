Sitemap XML
===========

Sitemap XML is not required feature but it may be very helpful.
You can read more at [sitemaps.org](https://www.sitemaps.org/).

Create configuration file somewhere.

```php
return [
    'static' => [
        'https://mysite.com',
        [
            'location' => 'https://mysite.com/news',
            'last_modified' => function (): Carbon {
                // complex calculations to determine the time
                // .....
                return $last_modified;
            },
            'change_frequency' => 'hourly',
            'priority' => 0.9,
        ],
        [
            'location' => fn(): string => route('about'),
            'last_modified' => '2010-01-01 00:00:00',
        ],
    ],
    'dynamic' => [
        [
            'collection' => fn(): iterable => Post::select('slug', 'updated_at')->active()->cursor(),
            'location' => fn(Post $model): string => route('post.view', $model),
            'last_modified' => fn(Post $model): Carbon => $model->updated_at,
            'change_frequency' => 'monthly',
            'priority' => 0.5,
        ],
    ],
];
```

Find `sitemap` section in `config/admin.php` and
set path to configuration file.

```php
'sitemap' => dirname(__DIR__) . '/app/sitemap.php',
```

There are 2 sub-sections:

- *static* - the list of locations for single static pages,
e.g. `/`, `/about`, `/contacts`.

- *dynamic* - the list of locations for resources,
e.g. `/posts/first-doc`, `/posts/other-doc`.

Then you can start creation of XML files by
clicking button on dashboard.
Or you can add console command `php artisan sitemap:generate` to schedule.

---

[Table of contents](./index.md)
