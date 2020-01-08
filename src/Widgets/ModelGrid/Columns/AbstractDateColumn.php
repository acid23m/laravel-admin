<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Class AbstractDateColumn.
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
        $this->setValue(static function (Model $item) use ($attribute): string {
            return Formatter::isoToLocalDate($item->$attribute);
        });
    }

}
