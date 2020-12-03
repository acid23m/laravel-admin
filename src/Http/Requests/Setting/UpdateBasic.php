<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests\Setting;

use SP\Admin\Contracts\Setting\AbstractBasicRequest;

/**
 * Form data for basic settings on update event.
 *
 * @package SP\Admin\Http\Requests\Setting
 */
class UpdateBasic extends AbstractBasicRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $admin_langs = implode(
            ',',
            array_keys(config('admin.languages'))
        );

        return [
            'app_name' => 'required|max:195',
            'app_logo' => 'nullable|image',
            'admin_lang' => "in:$admin_langs",
            'timezone' => 'timezone',
            'mail_gate_host' => 'max:255',
            'mail_gate_login' => 'max:255',
            'mail_gate_password' => 'max:255',
            'mail_gate_port' => 'integer',
            'mail_gate_encryption' => 'nullable|max:10',
            'mail_gate_from' => 'nullable|email:rfc',
        ];
    }

}
