<?php
declare(strict_types=1);

namespace SP\Admin\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Redirects to admin panel if authenticated.
 *
 * @package SP\Http\Middleware
 */
final class RedirectIfAuthenticated
{
    /**
     * Handles an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, \Closure $next, $guard = null): mixed
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/admin/home');
        }

        return $next($request);
    }

}
