<?php
declare(strict_types=1);

namespace SP\Admin\Models\Policies;

use Illuminate\Auth\Access\Response;
use SP\Admin\Models\User;
use SP\Admin\Security\Role;

/**
 * Client scripts policy.
 *
 * @package SP\Admin\Models\Policies
 */
final class SettingScriptPolicy extends AbstractPolicy
{
    /**
     * Determines whether the user can view the model.
     *
     * @param User $user
     * @return bool|Response
     */
    public function view(User $user): bool|Response
    {
        return $user->can(Role::ADMIN);
    }

    /**
     * Determines whether the user can update the model.
     *
     * @param User $user
     * @return bool|Response
     */
    public function update(User $user): bool|Response
    {
        return $user->can(Role::ADMIN);
    }

}
