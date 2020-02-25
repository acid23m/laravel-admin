<?php declare(strict_types=1);

use SP\Admin\Http\Requests\Setting\UpdateBasic;
use SP\Admin\Models\Repositories\SettingBasicRepository;
use SP\Admin\Models\SettingBasic;
use SP\Admin\Security\Role;

return [

    /*
    |--------------------------------------------------------------------------
    | List of available roles
    |--------------------------------------------------------------------------
    |
    | This value is the list of user roles for administrative panel.
    | It appears in role selectors when creating/updating users.
    | The definition is [role name => description].
    | There are two predefined roles: root (superuser) and admin.
    |
    */

    'roles' => [
        Role::ADMIN => 'Main Administrator with all Application permissions.',
    ],

    /*
    |--------------------------------------------------------------------------
    | List of available languages
    |--------------------------------------------------------------------------
    |
    | This value is the list of languages for administrative panel.
    | After extending this list,
    | create file {new lang}.json in the resources/lang/vendor/admin directory,
    | copy content from ru.json and finally translate it.
    |
    */

    'languages' => [
        'ru' => 'Русский',
        'en' => 'English',
    ],

    /*
    |--------------------------------------------------------------------------
    | Binds implementations of
    | basic application settings.
    |--------------------------------------------------------------------------
    |
    | You may extends basic application settings
    | by creating/extending your classes.
    |
    | basic_class : model that manipulates with attributes in database/basic.settings.ini file.
    | basic_repository_class : details for "show view" and help methods.
    | basic_request_class : validation rules.
    | disk : filesystem disk.
    |
    */
    'settings' => [
        'basic_class' => SettingBasic::class,
        'basic_repository_class' => SettingBasicRepository::class,
        'basic_request_class' => UpdateBasic::class,
        'disk' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | List of models that can be soft deleted.
    |--------------------------------------------------------------------------
    |
    | If model is deleted softly, they can be shown in one place - trash bin.
    |
    | Here you can define the path to configuration file.
    | 'trash_bin' => dirname(__DIR__) . '/app/trash_bin.php',
    |
    | Configuration file example:
    |
    | return [
    |     \App\Models\Post::class => [
    |         'group_name' => 'Posts',
    |         'label' => function (Post $model): string {
    |             return $model->id . ': ' . $model->title;
    |         },
    |         'links' => [
    |             'view' => function (Post $model): string {
    |                 return route('admin.posts.show', $model);
    |             },
    |             'restore' => function (Post $model): string {
    |                 return route('admin.posts.restore', $model);
    |             },
    |             'delete' => fn (Post $model) => route('admin.posts.force-delete', $model),
    |         ],
    |     ],
    | ],
    |
    | DO NOT FORGET TO ADD RESTORE/FORCE-DELETE FUNCTIONALITY
    | - actions to controller
    | - maybe buttons to view
    | - ...
    |
    */
    'trash_bin' => '',

    /*
    |--------------------------------------------------------------------------
    | On-the-fly image resizing.
    |--------------------------------------------------------------------------
    |
    | See https://glide.thephpleague.com/1.0/config/setup/ for details.
    |
    | base_url : for generating url
    | source_disk : where to searche files
    | cache_disk : where to put thumbs
    |
    */
    'image_resizer' => [
        'base_url' => 'img',
        'source_disk' => 'public',
        'cache_disk' => 'public',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locations for sitemap XML.
    |--------------------------------------------------------------------------
    |
    | Sitemaps are an easy way for webmasters to inform search engines
    | about pages on their sites that are available for crawling.
    |
    | Configuration example:
    |
    | 'sitemap' => [
    |     'static' => [
    |         'https://mysite.com',
    |         [
    |             'location' => 'https://mysite.com/news',
    |             'last_modified' => function (): Carbon {
    |                 // complex calculations to determine the time
    |                 // .....
    |                 return $last_modified;
    |             },
    |             'change_frequency' => 'hourly',
    |             'priority' => 0.9,
    |         ],
    |         [
    |             'location' => fn(): string => route('about'),
    |             'last_modified' => '2010-01-01 00:00:00',
    |         ],
    |     ],
    |     'dynamic' => [
    |         [
    |             'collection' => fn(): iterable => Post::select('slug', 'updated_at')->active()->cursor(),
    |             'location' => fn(Post $model): string => route('post.view', $model),
    |             'last_modified' => fn(Post $model): Carbon => $model->updated_at,
    |             'change_frequency' => 'monthly',
    |             'priority' => 0.5,
    |         ],
    |     ],
    | ],
    |
    */
    'sitemap' => [
        'static' => [
            [
                'location' => fn(): string => url(''),
                'priority' => 0.9,
            ],
        ],
        'dynamic' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Parsable log.
    |--------------------------------------------------------------------------
    |
    | Channel name defined in config/logging.php
    |
    */
    'log_channel' => 'single_2',

];
