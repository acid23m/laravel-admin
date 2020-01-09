Admin panel for Laravel
=======================

Administrative part for the Site.
Template is based on [Bootstrap 4](https://getbootstrap.com/) and [BootAdmin](https://bootadmin.net/).

This package provides some useful functionality:

- Users with roles
- Login / logout
- Application settings
- Widgets for Blade
- Dashboard tools

Installation
------------

The preferred way to install this extension
is through [Composer](http://getcomposer.org/download/).

```bash
composer require --prefer-dist acid23m/laravel-admin
```

Once the extension is installed, do next:

- Install `make` tool.
```bash
sudo apt install make
```
- Install package.
```bash
php artisan admin:install
php artisan vendor:publish
```

Usage
-----

Go to https://your.site.com/admin.

Read the [Documentation](./docs/index.md).

License
-------

All contents of this package are licensed under the [MIT license](./LICENSE).
