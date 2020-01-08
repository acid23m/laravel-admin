<?php
declare(strict_types=1);

namespace SP\Admin\Http\Requests;

use SP\Admin\Models\ScheduledTask;

/**
 * Class ScheduledTaskRequest.
 *
 * @package SP\Admin\Http\Requests
 */
final class ScheduledTaskRequest extends AbstractFormRequest
{
    /**
     * Attributes with boolean values.
     *
     * @var array
     */
    protected array $from_checkbox = [
        'report_only_error',
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
            'name' => 'bail|required|max:150',
            'min' => 'required|max:50',
            'hour' => 'required|max:50',
            'day' => 'required|max:50',
            'month' => 'required|max:50',
            'week_day' => 'required|max:50',
            'command' => 'string',
            'out_file' => 'nullable|max:255',
            'file_write_method' => 'integer',
            'report_email' => 'nullable|max:255|email:rfc',
            'report_only_error' => 'boolean',
            'active' => 'boolean',
        ];
    }

}
