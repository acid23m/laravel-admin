<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use SP\Admin\Contracts\Setting\AbstractBasic;

/**
 * Basic settings for application.
 *
 * @property string $app_name
 * @property string $admin_lang
 * @property string $timezone
 * @property string $mail_gate_host
 * @property string $mail_gate_login
 * @property string $mail_gate_password
 * @property int $mail_gate_port
 * @property string $mail_gate_encryption
 *
 * @package SP\Admin\Models
 */
class SettingBasic extends AbstractBasic
{
    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        return [
            'app_name' => trans('Application Name'),
            'app_logo' => trans('Company Logotype'),
            'admin_lang' => trans('Administrative Panel Language'),
            'timezone' => trans('Timezone'),
            'mail_gate_host' => trans('Server Name'),
            'mail_gate_login' => trans('User Name (Email)'),
            'mail_gate_password' => trans('User Password'),
            'mail_gate_port' => trans('Port'),
            'mail_gate_encryption' => trans('Encryption'),
        ];
    }

}
