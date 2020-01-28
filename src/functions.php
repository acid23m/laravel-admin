<?php declare(strict_types=1);

use Illuminate\Contracts\Container\BindingResolutionException;
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
     * @throws BindingResolutionException
     */
    function basic_settings(?string $key = null, $default = null)
    {
        /** @var AbstractBasic $basic_settings */
        $basic_settings = resolve(AbstractBasic::class);

        if ($key === null) {
            return $basic_settings->getAll();
        }

        return $basic_settings->get($key, $default);
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
     * @see https://glide.thephpleague.com/
     */
    function app_logo_url(array $params = []): string
    {
        /** @var AbstractBasicRepository $basic_settings_repository */
        $basic_settings_repository = resolve(AbstractBasicRepository::class);
        if (empty($params)) {
            return $basic_settings_repository->appLogoUrlOriginal();
        }

        return $basic_settings_repository->appLogoUrlResized($params);
    }
}
