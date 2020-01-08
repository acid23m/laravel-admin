<?php
declare(strict_types=1);

namespace SP\Admin\Contracts\Setting;

use SP\Admin\Http\Requests\AbstractFormRequest;

/**
 * Setting updating request.
 *
 * @package SP\Admin\Contracts\Setting
 */
abstract class AbstractBasicRequest extends AbstractFormRequest
{
    /**
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

}
