<?php
declare(strict_types=1);

namespace SP\Admin\Listeners\User;

use Illuminate\Support\Str;
use SP\Admin\Events\User\CreatingUser as CreatingUserEvent;

/**
 * Class CreatingUser.
 *
 * @package SP\Admin\Listeners\User
 */
class CreatingUser
{
    /**
     * Handles the event.
     *
     * @param CreatingUserEvent $event
     * @return void
     */
    public function handle(CreatingUserEvent $event): void
    {
        $event->user->setAttribute('id', Str::uuid()->toString());
    }

}
