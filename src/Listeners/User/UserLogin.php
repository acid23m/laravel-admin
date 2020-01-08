<?php
declare(strict_types=1);

namespace SP\Admin\Listeners\User;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Carbon;

/**
 * Class UserLogin.
 *
 * @package SP\Admin\Listeners\User
 */
class UserLogin
{
    /**
     * Handles the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event): void
    {
        $event->user->setAttribute('accessed_at', Carbon::now('UTC'));
        $event->user->setAttribute('ip', request()->getClientIp());
        $event->user->save();
    }

}
