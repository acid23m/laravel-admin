<?php
declare(strict_types=1);

namespace SP\Admin\UseCases\Databases;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;

/**
 * Database for scheduled tasks.
 *
 * @package SP\Admin\UseCases\Databases
 */
final class ScheduledTask
{
    public const DB_CONNECTION = 'scheduled_tasks';
    public const DB_NAME = 'scheduled_tasks.db';

    /**
     * @var DatabaseManager
     */
    private DatabaseManager $db;

    /**
     * ScheduledTask constructor.
     *
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Creates database.
     */
    public function create(): void
    {
        $db = config('database.connections.' . self::DB_CONNECTION . '.database');

        if (!\file_exists($db) || $db === ':memory:') {
            new \SQLite3($db);

            // creates tables
            $this->db->connection(self::DB_CONNECTION)
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
