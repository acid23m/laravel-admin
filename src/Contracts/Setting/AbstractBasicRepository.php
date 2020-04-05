<?php
declare(strict_types=1);

namespace SP\Admin\Contracts\Setting;

use Carbon\CarbonTimeZone;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;

/**
 * Settings repository.
 *
 * @package SP\Admin\Contracts\Setting
 */
abstract class AbstractBasicRepository
{
    /**
     * @var AbstractBasic
     */
    protected AbstractBasic $model;
    /**
     * @var Cache
     */
    protected Cache $cache;
    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * BasicSettingRepository constructor.
     *
     * @param AbstractBasic $model
     * @param Cache $cache
     * @param FilesystemFactory $f_factory
     */
    public function __construct(AbstractBasic $model, Cache $cache, FilesystemFactory $f_factory)
    {
        $this->model = $model;
        $this->cache = $cache;
        $this->filesystem = $f_factory->disk(config('admin.settings.disk', 'public'));
    }

    /**
     * Config for modelDetails widget.
     *
     * @return array [["label" => "Setting name", "value" => "Setting value"]]
     */
    abstract public function modelDetailsConfig(): array;

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
     * Sender address for "From" field in emails.
     *
     * @return string
     * @throws \Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException
     */
    public function mailFrom(): string
    {
        // user defined "from"
        $from = basic_settings('mail_gate_from');

        if (filled($from)) {
            return $from;
        }

        // gets from login if it is an email
        $mail_gate_login = basic_settings('mail_gate_login');
        /** @var Validator $validator */
        $validator = resolve(ValidatorFactory::class)->make(
            ['mail_gate_login' => $mail_gate_login],
            ['mail_gate_login' => 'email']
        );

        if (filled($mail_gate_login) && $validator->valid()) {
            return $mail_gate_login;
        }

        // creates from domain
        $domain = request()->getHost();

        return "robot@$domain";
    }

    /**
     * @return Filesystem
     */
    public function fs(): Filesystem
    {
        return $this->filesystem;
    }

    /**
     * Url to original main image.
     *
     * @return string
     */
    public function appLogoUrlOriginal(): string
    {
        /** @var string $app_logo */
        $app_logo = $this->model['app_logo'];

        return $this->fs()->url($app_logo);
    }

    /**
     * Url to resized main image.
     *
     * @param array $params
     * @return string
     * @throws \InvalidArgumentException
     * @see image_glide_url()
     */
    public function appLogoUrlResized(array $params = []): string
    {
        /** @var string $app_logo */
        $app_logo = $this->model['app_logo'];

        return image_glide_url($app_logo, $params);
    }

}
