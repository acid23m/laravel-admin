<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;

/**
 * Row for "updated_at" field.
 *
 * @package SP\Admin\View\Widgets\ModelDetails\Rows
 */
class UpdatedAtRow extends AbstractDateTimeRow
{
    /**
     * @var string
     */
    protected string $attribute = Model::UPDATED_AT;

}
