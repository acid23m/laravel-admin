Installation
============

The preferred way to install this extension
is through [Composer](http://getcomposer.org/download/).

```bash
composer require --prefer-dist acid23m/laravel-admin
```

To simplify running scripts install `make` tool.

```bash
sudo apt install make
```

Assets
-----

Package installer automatically do the things.
However you may need to compile assets manually.
Run `build` script in the package root directory.

```bash
make build-native
```

or docker variant

```bash
make build-docker
```

Setup
-----

Run installer.

```bash
php artisan admin:install
```

This will create sqlite databases in the database directory.

First user credentials are:

- admin
- 12345

Publish package.

```bash
php artisan vendor:publish --provider=SP\Admin\ServiceProvider
```

This will publish assets, configurations, views, translations.

Go to page */admin/login* to login.

---

[Table of contents](./index.md)
