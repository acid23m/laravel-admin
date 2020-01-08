<?php declare(strict_types=1);

return [
    'admin_users' => [
        'driver' => 'eloquent',
        'model' => \SP\Admin\Models\User::class,
    ],
];
