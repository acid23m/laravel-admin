<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Class AbstractDateRow.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
abstract class AbstractDateRow extends ModelRow
{
    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function boot(): void
    {
        // attribute
        $attribute = $this->getAttribute();

        // value
        $this->setValue(static function (Model $item) use ($attribute): string {
            return Formatter::isoToLocalDate($item->$attribute);
        });
    }

}
