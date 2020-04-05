<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Dashboard;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SP\Admin\Console\Commands\SitemapCommand;
use SP\Admin\Http\Controllers\AdminController;
use SP\Admin\Log\Paginator as LogPaginator;
use SP\Admin\Log\Parser as LogParser;
use SP\Admin\Models\User;
use SP\Admin\Security\Role;

/**
 * Homepage of the admin panel.
 *
 * @package SP\Admin\Http\Controllers\Dashboard
 */
final class HomeController extends AdminController
{
    /**
     * @var Kernel
     */
    private Kernel $console;

    /**
     * HomeController constructor.
     *
     * @param Kernel $console
     */
    public function __construct(Kernel $console)
    {
        $this->console = $console;

        parent::__construct();
    }

    /**
     * Displays homepage.
     *
     * @return ViewFactory|View
     */
    public function index(): View
    {
        $log_data = (new LogParser)->parse();
        $log_paginator = (new LogPaginator($log_data['items'], $log_data['total']))->paginate();

        return view('admin::home.index', \compact('log_paginator'));
    }

    /**
     * Saves notes for current logged in user.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function updateNotes(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'note' => 'max:65000',
        ]);

        /** @var User $user */
        $user = auth('admin')->user();

        if (!$user->fill($data)->save()) {
            return back()->with('error', trans('Error updating.'));
        }

        return redirect()
            ->route('admin.home')
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * Displays php info.
     *
     * @return View
     */
    public function phpinfo(): View
    {
        return view('admin::home.phpinfo');
    }

    /**
     * Clears application cache.
     *
     * @return RedirectResponse
     */
    public function clearCache(): RedirectResponse
    {
        if (auth('admin')->guest()) {
            return redirect()->route('admin.home');
        }

        \clearstatcache(true);

        if (\extension_loaded('Zend OPcache')) {
            \opcache_reset();
        }

        $this->console->call('cache:clear -n');
        $this->console->call('optimize:clear -n');
        $this->console->call('config:clear -n');
        $this->console->call('event:clear -n');
        $this->console->call('route:clear -n');
        $this->console->call('view:clear -n');
        try {
            $this->console->call('debugbar:clear -n');
        } catch (\Exception $e) {
        }

        return redirect()
            ->route('admin.home')
            ->with('success', trans('Cache cleared.'));
    }

    /**
     * Generates sitemap.
     *
     * @return RedirectResponse
     */
    public function createSitemap(): RedirectResponse
    {
        $this->console->queue(SitemapCommand::class);

        return redirect()
            ->route('admin.home')
            ->with('success', trans('Sitemap generated.'));
    }

    /**
     * Clears log file.
     *
     * @return RedirectResponse
     */
    public function clearLog(): RedirectResponse
    {
        if (auth('admin')->user()->cant(Role::SUPER)) {
            return redirect()->route('admin.home');
        }

        /** @var string|null $ch */
        $ch = config('admin.log_channel');
        if ($ch !== null) {
            $log_path = config("logging.channels.$ch.path");
            $f = \fopen($log_path, 'wb');
            \fclose($f);
        }


        return redirect()
            ->route('admin.home')
            ->with('success', trans('Log cleared.'));
    }

}
