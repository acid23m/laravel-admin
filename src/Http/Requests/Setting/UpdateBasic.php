<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests\Setting;

use SP\Admin\Contracts\Setting\AbstractBasicRequest;

/**
 * Class UpdateBasic.
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
        return [
            'app_name' => 'bail|required|max:195',
            'app_logo' => 'nullable|image',
            'admin_lang' => 'in:' . \implode(',', \array_keys(config('admin.languages'))),
            'timezone' => 'timezone',
            'mail_gate_host' => 'bail|required|max:255',
            'mail_gate_login' => 'bail|required|max:255|email:rfc',
            'mail_gate_password' => 'max:255',
            'mail_gate_port' => 'integer',
            'mail_gate_encryption' => 'max:20',
        ];
    }

}
