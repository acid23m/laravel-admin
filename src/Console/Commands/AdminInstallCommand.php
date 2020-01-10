<?php
declare(strict_types=1);

namespace SP\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use SP\Admin\Security\Role;

/**
 * Class AdminInstallCommand.
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
     * @param DatabaseManager $db
     * @param Hasher $hasher
     */
    public function handle(DatabaseManager $db, Hasher $hasher): void
    {
        // creates database for users
        $this->createUsers($db, $hasher);
        // creates database for scheduled tasks
        $this->createScheduledTasks($db);
        $this->info('Databases created.');

        // compiles assets
        \passthru('cd ' . ADMIN_PACKAGE_PATH . ' && make node && make build');
        $this->info('Assets compiled.');
    }

    /**
     * Users.
     *
     * @param DatabaseManager $db
     * @param Hasher $hasher
     */
    private function createUsers(DatabaseManager $db, Hasher $hasher): void
    {
        $db_file = database_path('admin_users.db');

        if (!\file_exists($db_file)) {
            new \SQLite3($db_file);

            $db_connection = $db->connection('admin_users');

            // creates tables
            $db_connection
                ->getSchemaBuilder()
                ->create('users', static function (Blueprint $table) {
                    $table->string('id')->primary();
                    $table->string('username')->unique();
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->string('role');
                    $table->text('note')->nullable();
                    $table->boolean('active')->default(true);
                    $table->string('ip')->nullable();
                    $table->timestamp('accessed_at')->nullable();
                    $table->rememberToken();
                    $table->timestamps();
                });

            $db_connection
                ->getSchemaBuilder()
                ->create('password_resets', static function (Blueprint $table) {
                    $table->string('email')->index();
                    $table->string('token');
                    $table->timestamp('created_at')->nullable();
                });

            // inserts users
            $db_connection
                ->table('users')
                ->insertOrIgnore([
                    [
                        'id' => Str::uuid()->toString(),
                        'username' => env('ADMIN_USER_NAME', 'admin'),
                        'email' => env('ADMIN_USER_EMAIL', 'admin@site.org'),
                        'password' => $hasher->make(env('ADMIN_USER_PASSWORD', '12345')),
                        'role' => Role::SUPER,
                        'remember_token' => Str::random(10),
                        'created_at' => Carbon::now('UTC'),
                        'updated_at' => Carbon::now('UTC'),
                    ],
                ]);
        }
    }

    /**
     * Tasks.
     *
     * @param DatabaseManager $db
     */
    private function createScheduledTasks(DatabaseManager $db): void
    {
        $db_file = database_path('scheduled_tasks.db');

        if (!\file_exists($db_file)) {
            new \SQLite3($db_file);

            $db_connection = $db->connection('scheduled_tasks');

            // creates tables
            $db_connection
                ->getSchemaBuilder()
                ->create('scheduled_tasks', static function (Blueprint $table) {
                    $table->integerIncrements('id');
                    $table->string('name')->unique();
                    $table->string('min')->default('*');
                    $table->string('hour')->default('*');
                    $table->string('day')->default('*');
                    $table->string('month')->default('*');
                    $table->string('week_day')->default('*');
                    $table->text('command');
                    $table->string('out_file')->nullable();
                    $table->unsignedTinyInteger('file_write_method')->default(0);
                    $table->string('report_email')->nullable();
                    $table->boolean('report_only_error')->default(false);
                    $table->boolean('active')->default(true);
                    $table->timestamps();
                });
        }
    }

}
