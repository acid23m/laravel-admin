<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CreatedAtColumn.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class UpdatedAtColumn extends ModelColumn
{
    /**
     * @var string
     */
    protected string $attribute = Model::UPDATED_AT;

}
