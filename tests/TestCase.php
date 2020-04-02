<?php
declare(strict_types=1);

namespace SP\Admin\Tests;

use SP\Admin\ServiceProvider;
use SP\Admin\UseCases\Databases\AdminUser;
use SP\Admin\UseCases\Databases\ScheduledTask;

/**
 * TestCase.
 *
 * @package SP\Admin\Tests
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    protected bool $createDatabases = true;

    /**
     * {@inheritDoc}
     */
    protected function getEnvironmentSetUp($app): void
    {
        // sets up databases
        $app['config']->set('database.connections.' . AdminUser::DB_CONNECTION . '.database', ':memory:');
        $app['config']->set('database.connections.scheduled_tasks.database', ':memory:');
    }

    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        if ($this->createDatabases) {
            resolve(AdminUser::class)->create();
            resolve(ScheduledTask::class)->create();
        }
    }

}
