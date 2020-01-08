<?php
declare(strict_types=1);

namespace SP\Admin\Events\User;

use Illuminate\Queue\SerializesModels;
use SP\Admin\Models\User;

/**
 * Class SavingUser.
 *
 * @package SP\Admin\Events\User
 */
class SavingUser
{
    use SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * SavingUser constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}
