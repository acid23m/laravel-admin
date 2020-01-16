<?php
declare(strict_types=1);

namespace SP\Admin\Listeners\User;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use SP\Admin\Events\User\SavingUser as SavingUserEvent;

/**
 * Handler for "saving" event for user.
 *
 * @package SP\Admin\Listeners\User
 */
class SavingUser
{
    /**
     * @var Hasher
     */
    private Hasher $hasher;

    /**
     * SavingUser constructor.
     *
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Handles the event.
     *
     * @param SavingUserEvent $event
     */
    public function handle(SavingUserEvent $event): void
    {
        // creates password hash and remember-token
        if ($event->user->password_form !== null && filled($event->user->password_form)) {
            $event->user->setAttribute('password', $this->hasher->make($event->user->password_form));
            $event->user->password_form = null;

            $event->user->setAttribute('remember_token', Str::random(10));
        }

        $attributes = $event->user->getAttributes();
        unset($attributes['password_form']);
        $event->user->setRawAttributes($attributes);
    }

}
