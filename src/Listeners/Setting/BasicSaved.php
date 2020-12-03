<?php
declare(strict_types=1);

namespace SP\Admin\Listeners\Setting;

use Illuminate\Contracts\Console\Kernel as Console;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use SP\Admin\Events\Setting\BasicSaved as BasicSavedEvent;
use Spatie\ImageOptimizer\OptimizerChain;

/**
 * Handler for "saved" event for basic settings.
 *
 * @package SP\Admin\Listeners\Setting
 */
class BasicSaved
{
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * BasicSaved constructor.
     *
     * @param Request $request
     * @param FilesystemFactory $f_factory
     * @param OptimizerChain $optimizer
     * @param Console $console
     */
    public function __construct(
        private Request $request,
        FilesystemFactory $f_factory,
        private OptimizerChain $optimizer,
        private Console $console
    ) {
        $this->filesystem = $f_factory->disk('public');
    }

    /**
     * Handles the event.
     *
     * @param BasicSavedEvent $event
     */
    public function handle(BasicSavedEvent $event): void
    {
        $setting_old = $event->setting_old;
        $setting = $event->setting;

        // saves image
        $app_logo = $this->request->file('app_logo');
        if ($app_logo !== null) {
            // deletes previous image
            if (isset($setting_old['app_logo']) && $setting_old['app_logo']) {
                $this->filesystem->delete($setting_old['app_logo']);
            }
            // remembers new image
            $setting->set(
                'app_logo',
                $this->filesystem->put($setting::IMAGE_DIRECTORY, $app_logo)
            );
            // optimizes image
            $this->optimizer->optimize(
                $this->filesystem->path($setting['app_logo'])
            );
        } else {
            $setting->set('app_logo', $setting_old['app_logo']);
        }

        // clears config cache
        $this->console->call('config:clear -n');
    }

}
