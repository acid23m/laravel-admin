<?php
declare(strict_types=1);

namespace SP\Admin\Models\Policies;

use Illuminate\Auth\Access\Response;
use SP\Admin\Models\ScheduledTask;
use SP\Admin\Models\User;
use SP\Admin\Security\Role;

/**
 * Class ScheduledTaskPolicy.
 *
 * @package SP\Admin\Models\Policies
 */
final class ScheduledTaskPolicy extends AbstractPolicy
{
    /**
     * Determines whether the user can view any models.
     *
     * @param User $user
     * @return bool|Response
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determines whether the user can view the model.
     *
     * @param User $user
     * @param ScheduledTask $model
     * @return bool|Response
     */
    public function view(User $user, ScheduledTask $model)
    {
        return true;
    }

    /**
     * Determines whether the user can create models.
     *
     * @param User $user
     * @return bool|Response
     */
    public function create(User $user)
    {
        return $user->can(Role::ADMIN);
    }

    /**
     * Determines whether the user can update the model.
     *
     * @param User $user
     * @param ScheduledTask $model
     * @return bool|Response
     */
    public function update(User $user, ScheduledTask $model)
    {
        return $user->can(Role::ADMIN);
    }

    /**
     * Determines whether the user can delete the model.
     *
     * @param User $user
     * @param ScheduledTask $model
     * @return bool|Response
     */
    public function delete(User $user, ScheduledTask $model)
    {
        return $user->can(Role::ADMIN);
    }

    /**
     * Determines whether the user can restore the model.
     *
     * @param User $user
     * @param ScheduledTask $model
     * @return bool|Response
     */
    public function restore(User $user, ScheduledTask $model)
    {
        return $user->can(Role::ADMIN);
    }

    /**
     * Determines whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ScheduledTask $model
     * @return bool|Response
     */
    public function forceDelete(User $user, ScheduledTask $model)
    {
        return $user->can(Role::ADMIN);
    }

}
