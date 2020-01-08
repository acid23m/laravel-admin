<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\PasswordBrokerFactory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use SP\Admin\Http\Controllers\Controller;
use SP\Admin\Http\Middleware\RedirectIfAuthenticated;

final class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    /**
     * @var PasswordBrokerFactory
     */
    private $password_broker;

    /**
     * Creates a new controller instance.
     *
     * @param PasswordBrokerFactory $password_broker
     */
    public function __construct(PasswordBrokerFactory $password_broker)
    {
        $this->password_broker = $password_broker;

        $this->middleware(RedirectIfAuthenticated::class . ':admin');
    }

    /**
     * {@inheritDoc}
     */
    public function showLinkRequestForm()
    {
        return view('admin::auth.passwords.email');
    }

    /**
     * {@inheritDoc}
     */
    public function broker()
    {
        return $this->password_broker->broker('admin_users');
    }

}
