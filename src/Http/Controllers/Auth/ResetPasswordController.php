<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Factory as AuthContract;
use Illuminate\Contracts\Auth\PasswordBrokerFactory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use SP\Admin\Http\Controllers\Controller;
use SP\Admin\Http\Middleware\RedirectIfAuthenticated;

final class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';
    /**
     * @var AuthContract
     */
    private $auth;
    /**
     * @var PasswordBrokerFactory
     */
    private $password_broker;

    /**
     * Create a new controller instance.
     *
     * @param AuthContract $auth
     * @param PasswordBrokerFactory $password_broker
     */
    public function __construct(AuthContract $auth, PasswordBrokerFactory $password_broker)
    {
        $this->auth = $auth;
        $this->password_broker = $password_broker;

        $this->middleware(RedirectIfAuthenticated::class . ':admin');
    }

    /**
     * {@inheritDoc}
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('admin::auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function guard()
    {
        return $this->auth->guard('admin');
    }

    /**
     * {@inheritDoc}
     */
    public function broker()
    {
        return $this->password_broker->broker('admin_users');
    }

}
