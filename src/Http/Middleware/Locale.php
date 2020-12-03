<?php
declare(strict_types=1);

namespace SP\Admin\Http\Middleware;

use Illuminate\Http\Request;

/**
 * Sets admin panel language.
 *
 * @package SP\Admin\Http\Middleware
 */
final class Locale
{
    /**
     * Handles an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        app()->setLocale(
            basic_settings('admin_lang', 'en')
        );

        return $next($request);
    }

}
