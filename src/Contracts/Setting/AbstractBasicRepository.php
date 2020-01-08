<?php
declare(strict_types=1);

namespace SP\Admin\Contracts\Setting;

use Carbon\CarbonTimeZone;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Settings repository.
 *
 * @package SP\Admin\Contracts\Setting
 */
abstract class AbstractBasicRepository
{
    /**
     * @var Cache
     */
    private Cache $cache;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * BasicSettingRepository constructor.
     *
     * @param Cache $cache
     * @param FilesystemFactory $f_factory
     */
    public function __construct(Cache $cache, FilesystemFactory $f_factory)
    {
        $this->cache = $cache;
        $this->filesystem = $f_factory->disk('public');
    }

    /**
     * Config for modelDetails widget.
     *
     * @param AbstractBasic $model
     * @return array [["label" => "Setting name", "value" => "Setting value"]]
     */
    abstract public function modelDetailsConfig(AbstractBasic $model): array;

    /**
     * Timezone options for \<select\> element.
     *
     * @return array
     */
    public function timezoneListForSelector(): array
    {
        return $this->cache->remember('tz_select_list', 60, static function () {
            $timezones = \timezone_identifiers_list();

            $tz_and_offset_list = [];
            foreach ($timezones as $timezone) {
                $tz_and_offset_list[] = [
                    'offset' => (new CarbonTimeZone($timezone))->toOffsetName(),
                    'region' => $timezone,
                ];
            }

            $offsets = \array_column($tz_and_offset_list, 'offset');
            $regions = \array_column($tz_and_offset_list, 'region');
            \array_multisort(
                $offsets, SORT_ASC, SORT_NUMERIC,
                $regions, SORT_ASC, SORT_NATURAL,
                $tz_and_offset_list
            );

            $list = [];
            foreach ($tz_and_offset_list as $item) {
                $offset = $item['offset'];
                $region = $item['region'];

                $list[$region] = "$region ($offset)";
            }

            return $list;
        });
    }

    /**
     * Encryption options for \<select\> element.
     *
     * @return array
     */
    public function mailEncryptionListForSelector(): array
    {
        return [
            '' => '',
            'ssl' => 'SSL',
            'tls' => 'TLS',
        ];
    }

    /**
     * @return Filesystem
     */
    public function storage(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * Url to main image.
     *
     * @param AbstractBasic $model
     * @param bool $original
     * @return string|null
     */
    public function appLogoUrl(AbstractBasic $model, bool $original = true): ?string
    {
        /** @var string $app_logo */
        $app_logo = $model['app_logo'];

        if (!$app_logo) {
            return null;
        }

        return $original
            ? $this->storage()->url($app_logo)
            : image_glide_url($app_logo, [
                'w' => 250,
            ]);
    }

}
