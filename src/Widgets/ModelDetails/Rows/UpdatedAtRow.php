<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UpdatedAtRow.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
class UpdatedAtRow extends AbstractDateTimeRow
{
    /**
     * @var string
     */
    protected string $attribute = Model::UPDATED_AT;

}
