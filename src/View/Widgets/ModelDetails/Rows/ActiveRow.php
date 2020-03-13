<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Row for "active" attribute.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
class ActiveRow extends ModelRow
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
