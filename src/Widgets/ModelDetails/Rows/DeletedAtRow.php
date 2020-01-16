<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;
use SP\Admin\Helpers\Formatter;

/**
 * Row for "deleted_at" field.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
class DeletedAtRow extends ModelRow
{
    /**
     * @var string
     */
    public string $attribute = 'deleted_at';

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
            if ($item->$attribute === null) {
                return '<span class="text-muted">-</span>';
            }

            return '<span class="text-danger">' . Formatter::isoToLocalDateTime($item->$attribute) . '</span>';
        });
    }

}
