<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Column for "created_at" field.
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
