<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Unit;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\Factory as Validator;
use SP\Admin\Security\Role;
use SP\Admin\Tests\TestCase;
use SP\Admin\UseCases\Databases\AdminUser;

class AdminUserDatabaseTest extends TestCase
{
    use DatabaseTransactions;

    protected bool $createDatabases = false;

    private $db;
    private $hasher;

    public function setUp(): void
    {
        parent::setUp();

        $this->hasher = resolve(Hasher::class);
        $this->db = resolve(DatabaseManager::class)->connection(AdminUser::DB_CONNECTION);
    }

    public function testCreateWithDefaultUser(): void
    {
        resolve(AdminUser::class)->create();

        $this->assertDatabaseHas('users', [
            'username' => AdminUser::DEFAULT_USERNAME,
            'email' => AdminUser::DEFAULT_EMAIL,
            'role' => Role::SUPER,
        ], AdminUser::DB_CONNECTION);

        $data = $this->db->select('select id, password from users');
        $password_hash = $data[0]->password;
        self::assertTrue($this->hasher->check(AdminUser::DEFAULT_PASSWORD, $password_hash));
    }

    public function testCreateWithEnvDefinedUser(): void
    {
        putenv('ADMIN_USER_NAME=super');
        putenv('ADMIN_USER_EMAIL=super_user@his.email');
        putenv('ADMIN_USER_PASSWORD=qwerty');

        resolve(AdminUser::class)->create();

        $this->assertDatabaseHas('users', [
            'username' => 'super',
            'email' => 'super_user@his.email',
            'role' => Role::SUPER,
        ], AdminUser::DB_CONNECTION);

        $data = $this->db->select('select password from users');
        $password_hash = $data[0]->password;
        self::assertTrue($this->hasher->check('qwerty', $password_hash));
    }

    public function testIdAsUUID(): void
    {
        resolve(AdminUser::class)->create();

        $data = $this->db->select('select id from users');
        $id = $data[0]->id;

        $validator = resolve(Validator::class)->make(
            ['id' => $id],
            ['id' => 'uuid']
        );
        self::assertTrue($validator->passes());
    }

    public function testIdAsCustom(): void
    {
        resolve(AdminUser::class)->create(['id' => 'abc123']);

        $data = $this->db->select('select id from users');
        $id = $data[0]->id;

        self::assertEquals('abc123', $id);
    }

}
