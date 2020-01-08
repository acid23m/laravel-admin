<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelDetails\Rows;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CreatedAtRow.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
class CreatedAtRow extends AbstractDateTimeRow
{
    /**
     * @var string
     */
    protected string $attribute = Model::CREATED_AT;

}
