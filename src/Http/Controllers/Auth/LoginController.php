<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Factory as AuthContract;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use SP\Admin\Http\Controllers\Controller;
use SP\Admin\Http\Middleware\RedirectIfAuthenticated;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @param AuthContract $auth
     */
    public function __construct(private AuthContract $auth)
    {
        $this->middleware(RedirectIfAuthenticated::class . ':admin')->except('logout');
    }

    /**
     * {@inheritDoc}
     */
    public function username(): string
    {
        return 'username';
    }

    /**
     * {@inheritDoc}
     */
    public function showLoginForm()
    {
        return view('admin::auth.login');
    }

    /**
     * {@inheritDoc}
     */
    protected function credentials(Request $request): array
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['active' => true],
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function guard()
    {
        return $this->auth->guard('admin');
    }

}
