Users and Permissions
=====================

Users for Administrative part are separated from mail site.
They are stored in own database and has own functionality.

The database locates in `database/admin_users.db` file.
Do not forget to add it to your `.gitignore` file.

Roles
-----

Backend Users has roles. By default there are 2 roles:

- Superuser
- Admin

Superuser is hidden and can do all possible things for the application.
You can add additional roles in the published configuration file.

```php
// config/admin.php

'roles' => [
    Role::ADMIN => 'Main Administrator with all Application permissions.',
    'moderator' => 'Site worker.',
],
```

Now you can use `moderator` role in gates and/or policies.

```php
// AppServiceProvider

Gate::define('moderator', static function ($user): bool {
    $allowed_roles = [Role::SUPER, Role::ADMIN, 'moderator'];

    return rescue(
        static function () use (&$allowed_roles, &$user): bool {
            return \in_array($user->role, $allowed_roles, true);
        },
        false
    );
});

// ...

auth('admin')->user()->can('moderator');
```

Guards
------

The package registers `admin` guard.

```php
auth('admin')->user();
```

All controllers must be protected with `\SP\Admin\Http\Middleware\Authenticate:admin` middleware.

```php
// Controller

public function __construct()
{
    $this->middleware('\SP\Admin\Http\Middleware\Authenticate:admin');
}
```

To simplify development of backend controllers
it is recommended to extend them from
`\SP\Admin\Http\Controllers\AdminController`
([code](../src/Http/Controllers/AdminController.php)).

Policies
--------

When you creating [policies](https://laravel.com/docs/6.x/authorization#creating-policies)
extend them from [AbstractPolicy](../src/Models/Policies/AbstractPolicy.php).
This always gives access for superuser.

---

[Table of contents](./index.md)
