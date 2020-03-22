<?php declare(strict_types=1);

use SP\Admin\UseCases\Databases\AdminUser;
use SP\Admin\UseCases\Databases\ScheduledTask;

return [
    AdminUser::DB_CONNECTION => [
        'driver' => 'sqlite',
        'database' => database_path(AdminUser::DB_NAME),
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
    ScheduledTask::DB_CONNECTION => [
        'driver' => 'sqlite',
        'database' => database_path(ScheduledTask::DB_NAME),
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
];
