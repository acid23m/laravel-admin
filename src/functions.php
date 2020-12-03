<?php declare(strict_types=1);

use League\Glide\Urls\UrlBuilderFactory;
use SP\Admin\Contracts\Setting\AbstractBasic;
use SP\Admin\Contracts\Setting\AbstractBasicRepository;
use SP\Admin\Models\Repositories\SettingAnalyticsRepository;
use SP\Admin\Models\Repositories\SettingScriptRepository;
use SP\Admin\Models\Repositories\TrashBinRepository;
use SP\Admin\Models\SettingAnalytics;
use SP\Admin\Models\SettingScript;

if (!\function_exists('basic_settings')) {
    /**
     * Gets basic settings.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function basic_settings(?string $key = null, $default = null): mixed
    {
        /** @var AbstractBasic $basic_settings */
        $basic_settings = resolve(AbstractBasic::class);

        return $key === null
            ? $basic_settings->getAll()
            : $basic_settings->get($key, $default);
    }
}

if (!\function_exists('analytics')) {
    /**
     * Registers analytic services.
     *
     * @return string
     */
    function analytics(): string
    {
        /** @var SettingAnalytics $settings */
        $settings = resolve(SettingAnalytics::class);
        $repository = new SettingAnalyticsRepository;

        $ga = $repository->registerGoogleAnalytics($settings);
        $ym = $repository->registerYandexMetrika($settings);

        return $ga . $ym;
    }
}

if (!\function_exists('head_scripts')) {
    /**
     * User scripts registered in the page head.
     *
     * @return string
     */
    function head_scripts(): string
    {
        return (new SettingScriptRepository)->getScriptTag(new SettingScript, SettingScript::HEAD);
    }
}

if (!\function_exists('bottom_scripts')) {
    /**
     * User scripts registered in the bottom of the page.
     *
     * @return string
     */
    function bottom_scripts(): string
    {
        return (new SettingScriptRepository)->getScriptTag(new SettingScript, SettingScript::BOTTOM);
    }
}

if (!\function_exists('trash_bin_count')) {
    /**
     * Number of all trashed items.
     *
     * @return int
     */
    function trash_bin_count(): int
    {
        return resolve(TrashBinRepository::class)->count();
    }
}

if (!\function_exists('image_glide_url')) {
    /**
     * Url for on-the-fly resized images.
     *
     * @param string $path
     * @param array $params
     * @return string
     * @throws InvalidArgumentException
     * @see https://glide.thephpleague.com/
     */
    function image_glide_url(string $path, array $params = []): string
    {
        $base_url = config('admin.image_resizer.base_url', 'img');
        $sign_key = config('app.key');
        $url_builder = UrlBuilderFactory::create("/$base_url/", $sign_key);

        return $url_builder->getUrl($path, $params);
    }
}

if (!\function_exists('app_logo_url')) {
    /**
     * Url to company logotype.
     *
     * @param array $params Resizing parameters
     * @return string
     * @throws InvalidArgumentException
     * @see https://glide.thephpleague.com/
     */
    function app_logo_url(array $params = []): string
    {
        /** @var AbstractBasicRepository $basic_settings_repository */
        $basic_settings_repository = resolve(AbstractBasicRepository::class);

        return empty($params)
            ? $basic_settings_repository->appLogoUrlOriginal()
            : $basic_settings_repository->appLogoUrlResized($params);
    }
}
