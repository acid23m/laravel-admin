<?php
declare(strict_types=1);

namespace SP\Admin\Events\Setting;

use Illuminate\Queue\SerializesModels;
use SP\Admin\Contracts\Setting\AbstractBasic;

/**
 * The event triggers when basic settings has been saved.
 *
 * @package SP\Admin\Events\Setting
 */
class BasicSaved
{
    use SerializesModels;

    /**
     * @var array
     */
    public array $setting_old;
    /**
     * @var AbstractBasic
     */
    public AbstractBasic $setting;

    /**
     * BasicSaved constructor.
     *
     * @param array $setting_old
     * @param AbstractBasic $setting
     */
    public function __construct(array $setting_old, AbstractBasic $setting)
    {
        $this->setting_old = $setting_old;
        $this->setting = $setting;
    }

}
