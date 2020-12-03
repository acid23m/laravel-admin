<?php
declare(strict_types=1);

namespace SP\Admin\Security;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * User roles.
 *
 * @package SP\Admin\Security
 */
class Role
{
    public const SUPER = 'root';
    public const ADMIN = 'admin';

    /**
     * List of all roles.
     *
     * @static
     * @param bool $with_description
     * @param bool|null $with_superuser true = remove superuser; false = include superuser; null = auto
     * @return Collection
     */
    public static function getList(bool $with_description = true, $with_superuser = null): Collection
    {
        $mandatory_roles = [
            self::SUPER => 'Superuser with all permissions.',
            self::ADMIN => 'Main User of Administrative panel with all Application permissions.',
        ];
        $config_roles = config('admin.roles');

        $roles = collect(
            array_merge($mandatory_roles, $config_roles)
        );

        if (
            $with_superuser === false
            || ($with_superuser === null && Auth::guard('admin')->user()->cant(self::SUPER))
        ) {
            $roles = $roles->except([self::SUPER]);
        }

        if ($with_description === false) {
            $roles = $roles->keys();
        }

        return $roles;
    }

}
