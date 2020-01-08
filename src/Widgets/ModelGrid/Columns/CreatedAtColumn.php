<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CreatedAtColumn.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class CreatedAtColumn extends AbstractDateTimeColumn
{
    /**
     * @var string
     */
    protected string $attribute = Model::CREATED_AT;

}
