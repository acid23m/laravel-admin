<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers;

use SP\Admin\Http\Middleware\Authenticate;

/**
 * Base controller for authorized user.
 *
 * @package SP\Admin\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware(Authenticate::nameWithGuard());
    }

}
