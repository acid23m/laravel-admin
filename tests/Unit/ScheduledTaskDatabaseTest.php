<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Unit;

use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use SP\Admin\Tests\TestCase;
use SP\Admin\UseCases\Databases\ScheduledTask;

class ScheduledTaskDatabaseTest extends TestCase
{
    use DatabaseTransactions;

    protected bool $createDatabases = false;

    private $db;

    public function setUp(): void
    {
        parent::setUp();

        $this->db = resolve(DatabaseManager::class)->connection(ScheduledTask::DB_CONNECTION);
    }

    public function testCreate(): void
    {
        resolve(ScheduledTask::class)->create();

        $this->assertDatabaseMissing('scheduled_tasks', [
            'id' => 1,
        ], ScheduledTask::DB_CONNECTION);
    }

}
