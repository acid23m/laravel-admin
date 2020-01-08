<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests\User;

use SP\Admin\Http\Requests\AbstractFormRequest;
use SP\Admin\Security\Role;

/**
 * User creation request.
 *
 * @package SP\Admin\Http\Requests\User
 */
final class StoreUser extends AbstractFormRequest
{
    /**
     * Attributes with boolean values.
     *
     * @var array
     */
    protected array $from_checkbox = [
        'active',
    ];

    /**
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'bail|required|unique:admin_users.users|min:2|max:50|regex:/^([a-zA-Z0-9_])+$/',
            'email' => 'bail|required|unique:admin_users.users|max:255|email:rfc,dns,spoof',
            'password_form' => 'bail|required|min:5|max:50|regex:/^([a-zA-Z0-9_~!\@\#\$\%\^\&\*\(\)])+$/',
            'role' => 'in:' . \implode(',', Role::getList(false, true)->toArray()),
            'note' => 'max:65000',
            'active' => 'boolean',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function validationData(): array
    {
        $data = parent::validationData();

        // superuser is always active
        if ($data['role'] === Role::SUPER) {
            $data['active'] = '1';
        }

        return $data;
    }

}
