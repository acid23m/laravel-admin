<?php
declare(strict_types=1);

namespace SP\Admin\Tests;

use SP\Admin\ServiceProvider;

/**
 * Class TestCase.
 *
 * @package SP\Admin\Tests
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // perform environment setup
    }

}
