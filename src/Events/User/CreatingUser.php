<?php
declare(strict_types=1);

namespace SP\Admin\Events\User;

use Illuminate\Queue\SerializesModels;
use SP\Admin\Models\User;

/**
 * The event triggers when user is creating.
 *
 * @package SP\Admin\Events\User
 */
class CreatingUser
{
    use SerializesModels;

    /**
     * CreatingUser constructor.
     *
     * @param User $user
     */
    public function __construct(public User $user)
    {
    }

}
