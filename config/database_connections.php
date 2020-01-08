<?php declare(strict_types=1);

return [
    'admin_users' => [
        'driver' => 'sqlite',
        'database' => database_path('admin_users.db'),
        'prefix' => '',
        'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    ],
    'scheduled_tasks' => [
        'driver' => 'sqlite',
        'database' => database_path('scheduled_tasks.db'),
        'prefix' => '',
        'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    ],
];
