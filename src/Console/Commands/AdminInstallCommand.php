<?php
declare(strict_types=1);

namespace SP\Admin\Console\Commands;

use Illuminate\Console\Command;
use SP\Admin\UseCases\Databases\AdminUser;
use SP\Admin\UseCases\Databases\ScheduledTask;

/**
 * Console command which installs Administrative panel.
 *
 * @package SP\Admin\Console\Commands
 */
final class AdminInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepares administrative panel.';

    /**
     * Executes the console command.
     *
     * @param AdminUser $adminUser
     * @param ScheduledTask $scheduledTask
     */
    public function handle(AdminUser $adminUser, ScheduledTask $scheduledTask): void
    {
        // creates database for users
        $adminUser->create();
        // creates database for scheduled tasks
        $scheduledTask->create();
        $this->info('Databases created.');

        // compiles assets
        \passthru('cd ' . ADMIN_PACKAGE_PATH . ' && make node && make build');
        $this->info('Assets compiled.');
    }

}
