<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Column for "updated_at" field.
 *
 * @package SP\Admin\View\Widgets\ModelGrid\Columns
 */
class UpdatedAtColumn extends ModelColumn
{
    /**
     * @var string
     */
    protected string $attribute = Model::UPDATED_AT;

}
