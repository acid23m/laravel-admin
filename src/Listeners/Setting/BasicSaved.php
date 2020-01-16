<?php
declare(strict_types=1);

namespace SP\Admin\Listeners\Setting;

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
     * @var Request
     */
    private Request $request;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;
    /**
     * @var OptimizerChain
     */
    private OptimizerChain $optimizer;

    /**
     * BasicSaved constructor.
     *
     * @param Request $request
     * @param FilesystemFactory $f_factory
     * @param OptimizerChain $optimizer
     */
    public function __construct(Request $request, FilesystemFactory $f_factory, OptimizerChain $optimizer)
    {
        $this->request = $request;
        $this->filesystem = $f_factory->disk('public');
        $this->optimizer = $optimizer;
    }

    /**
     * Handles the event.
     *
     * @param BasicSavedEvent $event
     */
    public function handle(BasicSavedEvent $event): void
    {
        $setting_old = $event->setting_old;
        $setting_new = $event->setting_new;

        // saves image
        $app_logo = $this->request->file('app_logo');
        if ($app_logo !== null) {
            // deletes previous image
            if ($setting_old['app_logo']) {
                $this->filesystem->delete($setting_old['app_logo']);
            }
            // remembers new image
            $setting_new->set(
                'app_logo',
                $this->filesystem->put($setting_new::IMAGE_DIRECTORY, $app_logo)
            );
            // optimizes image
            $this->optimizer->optimize(
                $this->filesystem->path($setting_new['app_logo'])
            );
        } else {
            $setting_new->set('app_logo', '');
        }
    }

}
