<?php
declare(strict_types=1);

namespace SP\Admin\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

/**
 * Redirects to login page for authentication.
 *
 * @package SP\Http\Middleware
 */
final class Authenticate extends Middleware
{
    /**
     * {@inheritDoc}
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }

        return null;
    }

    /**
     * Middleware name/alias with parameters.
     *
     * @static
     * @return string
     */
    public static function nameWithGuard(): string
    {
        $guard = \array_key_first(
            require ADMIN_PACKAGE_PATH . '/config/auth_guards.php'
        );

        return __CLASS__ . ':' . $guard;
    }

}
