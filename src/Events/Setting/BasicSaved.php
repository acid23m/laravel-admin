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
     * BasicSaved constructor.
     *
     * @param array $setting_old
     * @param AbstractBasic $setting
     */
    public function __construct(public array $setting_old, public AbstractBasic $setting)
    {
    }

}
