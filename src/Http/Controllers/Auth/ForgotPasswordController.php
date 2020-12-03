<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\PasswordBrokerFactory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use SP\Admin\Http\Controllers\Controller;
use SP\Admin\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\View\View;

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
     * Creates a new controller instance.
     *
     * @param PasswordBrokerFactory $password_broker
     */
    public function __construct(private PasswordBrokerFactory $password_broker)
    {
        $this->middleware(RedirectIfAuthenticated::class . ':admin');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return View
     */
    public function showLinkRequestForm()
    {
        return view('admin::auth.passwords.email');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        return $this->password_broker->broker('admin_users');
    }

}
