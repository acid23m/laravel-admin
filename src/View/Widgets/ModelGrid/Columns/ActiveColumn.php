<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Predefined column for "active" field.
 *
 * @package SP\Admin\View\Widgets\ModelGrid\Columns
 */
class ActiveColumn extends ModelColumn
{
    /**
     * @var string
     */
    protected string $attribute = 'active';

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function boot(): void
    {
        // filter
        $this->setFilter([
            'true' => trans('Yes'),
            'false' => trans('No'),
        ]);

        // value
        $attribute = $this->getAttribute();
        $this->setValue(static function (Model $item) use ($attribute): string {
            $value = $item->$attribute;

            return $value
                ? '<span class="badge badge-success">' . Formatter::booleanToString($value) . '</span>'
                : '<span class="badge badge-danger">' . Formatter::booleanToString($value) . '</span>';
        });
    }

}
