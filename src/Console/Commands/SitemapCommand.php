<?php
declare(strict_types=1);

namespace SP\Admin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

/**
 * Console command which creates sitemap.
 *
 * @package SP\Admin\Console\Commands
 */
final class SitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Sitemap XML files.';

    /**
     * Executes the console command.
     */
    public function handle(): void
    {
        $sitemap_path = public_path('sitemap.xml');

        // removes files
        \passthru('rm -f ' . public_path() . '/*sitemap.xml');
        // clears config cache
        \passthru('artisan -n config:clear');

        $sitemap_files = [];

        // static pages
        $static_sitemap = $this->staticPages();
        if ($static_sitemap !== null) {
            $sitemap_files[] = $static_sitemap;
        }
        unset($static_sitemap);

        // dynamic pages
        $sitemap_files = \array_merge($sitemap_files, $this->dynamicPages());

        // creates index file
        if (!empty($sitemap_files)) {
            $sitemap_index = SitemapIndex::create();

            foreach ($sitemap_files as &$sitemap_file) {
                $sitemap_index->add($sitemap_file);
            }
            unset($sitemap_file, $sitemap_files);

            $f = \fopen($sitemap_path, 'wb');
            \fclose($f);

            $sitemap_index->writeToFile($sitemap_path);
        }

        $this->info('Sitemap generated.');
    }

    /**
     * Creates sitemap file for static pages.
     *
     * @return string|null
     * @throws \Throwable
     */
    private function staticPages(): ?string
    {
        $sitemap_path = public_path('static_sitemap.xml');
        $sitemap_url = url('static_sitemap.xml');

        $pages = config('admin.sitemap.static');

        if ($pages === null || empty($pages)) {
            return null;
        }

        $sitemap = Sitemap::create();

        foreach ($pages as &$page) {
            if (\is_string($page)) {
                $sitemap->add($page);
            } elseif (\is_array($page)) {
                throw_if(
                    !isset($page['location']),
                    \LogicException::class,
                    'At least the "location" must be set.'
                );

                $url = Url::create(
                    value($page['location'])
                );

                if (isset($page['last_modified'])) {
                    $last_mod = value($page['last_modified']);

                    if (\is_string($last_mod)) {
                        $url->setLastModificationDate(
                            Carbon::parse($last_mod, config('app.timezone', 'UTC'))
                        );
                    } elseif ($last_mod instanceof \DateTime) {
                        $url->setLastModificationDate($last_mod);
                    }
                }

                if (isset($page['change_frequency'])) {
                    $url->setChangeFrequency($page['change_frequency']);
                }

                if (isset($page['priority'])) {
                    $url->setPriority($page['priority']);
                }

                $sitemap->add($url);
            }
        }
        unset($page, $pages);

        $f = \fopen($sitemap_path, 'wb');
        \fclose($f);

        $sitemap->writeToFile($sitemap_path);

        return $sitemap_url;
    }

    /**
     * Creates sitemap file for dynamic pages.
     *
     * @return array
     * @throws \Throwable
     */
    public function dynamicPages(): array
    {
        $list = [];

        $pages = config('admin.sitemap.dynamic');

        if ($pages === null || empty($pages)) {
            return [];
        }

        foreach ($pages as &$page_collection) {
            throw_if(
                !isset($page_collection['collection']),
                \LogicException::class,
                'The "collection" is not set.'
            );
            throw_if(
                !isset($page_collection['location']),
                \LogicException::class,
                'The "location" is not set.'
            );

            $sitemap_path = $sitemap_url = null;
            $sitemap = Sitemap::create();

            $collection = value($page_collection['collection']);
            /** @var object $model */
            foreach ($collection as $model) {
                $name = Str::snake(
                    \last(
                        \explode('\\', \get_class($model))
                    )
                );

                if ($sitemap_path === null && $sitemap_url === null) {
                    $sitemap_path = public_path("{$name}_sitemap.xml");
                    $sitemap_url = url("{$name}_sitemap.xml");
                }

                $url = Url::create(
                    $page_collection['location'] instanceof \Closure
                        ? with($model, $page_collection['location'])
                        : $page_collection['location']
                );

                if (isset($page_collection['last_modified'])) {
                    $last_mod = $page_collection['last_modified'] instanceof \Closure
                        ? with($model, $page_collection['last_modified'])
                        : $page_collection['last_modified'];

                    if (\is_string($last_mod)) {
                        $url->setLastModificationDate(
                            Carbon::parse($last_mod, config('app.timezone', 'UTC'))
                        );
                    } elseif ($last_mod instanceof \DateTime) {
                        $url->setLastModificationDate($last_mod);
                    }
                }

                if (isset($page_collection['change_frequency'])) {
                    $url->setChangeFrequency($page_collection['change_frequency']);
                }

                if (isset($page_collection['priority'])) {
                    $url->setPriority($page_collection['priority']);
                }

                $sitemap->add($url);
            }
            unset($model, $collection);

            if ($sitemap_path !== null && $sitemap_url !== null) {
                $f = \fopen($sitemap_path, 'wb');
                \fclose($f);

                $sitemap->writeToFile($sitemap_path);
                $list[] = $sitemap_url;
            }
        }
        unset($page_collection, $pages, $sitemap);

        return $list;
    }

}
