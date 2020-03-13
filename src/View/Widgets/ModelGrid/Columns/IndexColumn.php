<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Column with row number.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class IndexColumn extends Column
{
    /**
     * @var string|null
     */
    protected ?string $label = '#';
    /**
     * @var int|null
     */
    protected ?int $cell_width = 60;

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        // value
        $this->setValue(static function (Model $item, int $index): string {
            $number = $index + 1;

            return (string)$number;
        });
    }

}
