<?php
declare(strict_types=1);

namespace SP\Admin\Events\Setting;

use Illuminate\Queue\SerializesModels;
use SP\Admin\Contracts\Setting\AbstractBasic;

/**
 * Class BasicSaved.
 *
 * @package SP\Admin\Events\Setting
 */
class BasicSaved
{
    use SerializesModels;

    /**
     * @var AbstractBasic
     */
    public AbstractBasic $setting_old;
    /**
     * @var AbstractBasic
     */
    public AbstractBasic $setting_new;

    /**
     * BasicSaved constructor.
     *
     * @param AbstractBasic $setting_old
     * @param AbstractBasic $setting_new
     */
    public function __construct(AbstractBasic $setting_old, AbstractBasic $setting_new)
    {
        $this->setting_old = $setting_old;
        $this->setting_new = $setting_new;
    }

}
