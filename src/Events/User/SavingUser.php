<?php
declare(strict_types=1);

namespace SP\Admin\Events\User;

use Illuminate\Queue\SerializesModels;
use SP\Admin\Models\User;

/**
 * The event triggers when user is saving.
 *
 * @package SP\Admin\Events\User
 */
class SavingUser
{
    use SerializesModels;

    /**
     * SavingUser constructor.
     *
     * @param User $user
     */
    public function __construct(public User $user)
    {
    }

}
