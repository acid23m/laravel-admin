<?php
declare(strict_types=1);

namespace SP\Admin\Contracts\Setting;

use SP\Admin\Models\AbstractIniSetting;

/**
 * Stores basic application settings in ini file.
 *
 * @package SP\Admin\Contracts\Setting
 */
abstract class AbstractBasic extends AbstractIniSetting
{
    public const IMAGE_DIRECTORY = 'settings';

    /**
     * {@inheritDoc}
     */
    public function filePath(): string
    {
        return database_path('basic.settings.ini');
    }

    /**
     * {@inheritDoc}
     */
    protected function sectionName(): string
    {
        return 'basic';
    }

}
