<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base form request.
 *
 * @package SP\Admin\Http\Requests
 */
abstract class AbstractFormRequest extends FormRequest
{
    /**
     * Attributes with boolean values.
     *
     * @var array
     */
    protected array $from_checkbox = [];

    /**
     * Determines if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $data = $this->all();

        // unchecked checkboxes do not send data
        // add "false" value manually
        foreach ($this->from_checkbox as $attribute) {
            if (!isset($data[$attribute])) {
                $data[$attribute] = '0';
            }
        }

        return $data;
    }

}
