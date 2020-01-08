<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateAnalytics.
 *
 * @package SP\Admin\Http\Requests\Setting
 */
final class UpdateAnalytics extends FormRequest
{
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
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'google' => 'max:50',
            'yandex' => 'max:50',
        ];
    }

}
