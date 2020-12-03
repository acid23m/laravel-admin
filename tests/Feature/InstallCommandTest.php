<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use SP\Admin\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    use DatabaseTransactions;

    protected bool $createDatabases = false;

    private string $node_modules_path = './node_modules';
    private string $js_dist_path = './resources/js/dist';
    private string $css_dist_path = './resources/css/dist';
    private bool $system_node = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->node_modules_path = ADMIN_PACKAGE_PATH . '/node_modules';
        $this->js_dist_path = ADMIN_PACKAGE_PATH . '/resources/js/dist';
        $this->css_dist_path = ADMIN_PACKAGE_PATH . '/resources/css/dist';

        $node_version = 13;
        passthru('node -v', $v);
        if (!str_starts_with($v, "v$node_version")) {
            $this->system_node = false;
            passthru("curl -sL https://deb.nodesource.com/setup_$node_version.x | bash - && apt-get install -y build-essential nodejs");
        }
    }

    public function testCommand(): void
    {
        $this->artisan('admin:install')
            ->expectsOutput('Databases created.')
            ->expectsOutput('Assets compiled.')
            ->assertExitCode(0);

        self::assertDirectoryExists($this->node_modules_path);
        self::assertDirectoryExists($this->js_dist_path);
        self::assertDirectoryExists($this->css_dist_path);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        passthru("rm -rf $this->node_modules_path");
        passthru("rm -rf $this->js_dist_path");
        passthru("rm -rf $this->css_dist_path");
        passthru('rm -rf ' . ADMIN_PACKAGE_PATH . '/resources/css/fonts');
        passthru('rm -rf ' . ADMIN_PACKAGE_PATH . '/resources/css/font');

        if (!$this->system_node) {
            passthru('apt-get remove -y nodejs');
        }
    }

}
