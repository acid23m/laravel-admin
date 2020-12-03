<?php
declare(strict_types=1);

namespace SP\Admin\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as IlluminateResetPasswordNotification;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notifies about password reset.
 *
 * @package SP\Admin\Notifications
 */
final class ResetPassword extends IlluminateResetPasswordNotification
{
    /**
     * @var Translator
     */
    private Translator $lang;

    /**
     * ResetPassword constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        parent::__construct($token);

        $this->lang = resolve(Translator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function toMail($notifiable)
    {
        if (self::$toMailCallback) {
            return call_user_func(self::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject($this->lang->get('Reset Password Notification') . ' | ' . config('app.name'))
            ->line(
                $this->lang->get('You are receiving this email because we received a password reset request for your account.')
            )
            ->action(
                $this->lang->get('Reset Password'),
                url(
                    config('app.url')
                    . route(
                        'admin.password.reset',
                        ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()],
                        false
                    )
                )
            )
            ->line($this->lang->get('This password reset link will expire in :count minutes.', [
                'count' => config('auth.passwords.admin_users.expire'),
            ]))
            ->line($this->lang->get('If you did not request a password reset, no further action is required.'));
    }

}
