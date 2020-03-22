<?php
declare(strict_types=1);

namespace SP\Admin\UseCases\Databases;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use SP\Admin\Security\Role;

/**
 * Database for admins.
 *
 * @package SP\Admin\UseCases\Databases
 */
final class AdminUser
{
    public const DB_CONNECTION = 'admin_users';
    public const DB_NAME = 'admin_users.db';

    /**
     * @var Connection
     */
    private Connection $db;
    /**
     * @var Hasher
     */
    private Hasher $hasher;

    /**
     * AdminUser constructor.
     *
     * @param DatabaseManager $db
     * @param Hasher $hasher
     */
    public function __construct(DatabaseManager $db, Hasher $hasher)
    {
        $this->db = $db->connection(self::DB_CONNECTION);
        $this->hasher = $hasher;
    }

    /**
     * Creates database.
     *
     * @param array|null $default_user [username, email, password]
     */
    public function create(?array $default_user = null): void
    {
        $db = config('database.connections.' . self::DB_CONNECTION . '.database');

        if ($db === ':memory:' || !\file_exists($db)) {
            new \SQLite3($db);

            // creates tables
            $this->db
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

            $this->db
                ->getSchemaBuilder()
                ->create('password_resets', static function (Blueprint $table) {
                    $table->string('email')->index();
                    $table->string('token');
                    $table->timestamp('created_at')->nullable();
                });

            // inserts users
            $username = $default_user['username'] ?? env('ADMIN_USER_NAME', 'admin');
            $email = $default_user['email'] ?? env('ADMIN_USER_EMAIL', 'admin@site.org');
            $password = $default_user['password'] ?? env('ADMIN_USER_PASSWORD', '12345');

            $this->db
                ->table('users')
                ->insertOrIgnore([
                    [
                        'id' => Str::uuid()->toString(),
                        'username' => $username,
                        'email' => $email,
                        'password' => $this->hasher->make($password),
                        'role' => Role::SUPER,
                        'remember_token' => Str::random(10),
                        'created_at' => Carbon::now('UTC'),
                        'updated_at' => Carbon::now('UTC'),
                    ],
                ]);
        }
    }

}
