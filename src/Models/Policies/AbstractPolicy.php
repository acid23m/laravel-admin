<?php
declare(strict_types=1);

namespace SP\Admin\Models\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use SP\Admin\Models\User;
use SP\Admin\Security\Role;

/**
 * Base policy class.
 *
 * @package SP\Admin\Models\Policies
 */
abstract class AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Policy pre-check.
     *
     * @param User $user
     * @param $ability
     * @return bool|null
     */
    public function before(User $user, $ability): ?bool
    {
        if ($user->role === Role::SUPER) {
            return true;
        }

        return null;
    }

}
