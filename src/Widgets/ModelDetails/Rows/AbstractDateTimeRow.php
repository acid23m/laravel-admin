<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Base class for rows with dates and times.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
abstract class AbstractDateTimeRow extends ModelRow
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
        $this->setValue(fn(Model $item) => Formatter::isoToLocalDateTime($item->$attribute));
    }

}
