<?php
declare(strict_types=1);

namespace SP\Admin\Models;

/**
 * Analytics for site.
 *
 * @property string $google
 * @property string $yandex
 *
 * @package SP\Admin\Models
 */
final class SettingAnalytics extends AbstractIniSetting
{
    /**
     * {@inheritDoc}
     */
    public function filePath(): string
    {
        return database_path('analytics.settings.ini');
    }

    /**
     * {@inheritDoc}
     */
    protected function sectionName(): string
    {
        return 'analytics';
    }

    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        return [
            'google' => trans('Google Analytics Tracking ID'),
            'yandex' => trans('Yandex Metrica Counting Number'),
        ];
    }

}
