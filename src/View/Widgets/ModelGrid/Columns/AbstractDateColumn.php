<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Base class for columns with dates.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
abstract class AbstractDateColumn extends AbstractDateTimeColumn
{
    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function boot(): void
    {
        parent::boot();

        // attribute
        $attribute = $this->getAttribute();

        // value
        $this->setValue(fn(Model $item) => Formatter::isoToLocalDate($item->$attribute));
    }

}
