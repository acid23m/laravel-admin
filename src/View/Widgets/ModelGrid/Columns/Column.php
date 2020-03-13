<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelGrid\Columns;

/**
 * Column for grid.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class Column
{
    /**
     * @var string|null
     */
    protected ?string $label;
    /**
     * @var string|\Closure|null
     */
    protected $value;
    /**
     * @var int|null
     */
    protected ?int $cell_width;
    /**
     * @var string|null
     */
    protected ?string $cell_class;

    /**
     * Initializes column.
     */
    public function boot(): void
    {
        // init
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        try {
            return $this->label;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return string|\Closure|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Cell value.
     *
     * @param string|\Closure|null $value
     * Possible values are:
     * - null : the grid will try to get value from model by attribute.
     * - string : this string will render.
     * - function : the definition is `function (Model $item, int $index): string {}`.
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getCellWidth(): ?int
    {
        try {
            return $this->cell_width;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param int|null $cell_width
     */
    public function setCellWidth(?int $cell_width): void
    {
        $this->cell_width = $cell_width;
    }

    /**
     * @return string|null
     */
    public function getCellClass(): ?string
    {
        try {
            return $this->cell_class;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param string|null $cell_class
     */
    public function setCellClass(?string $cell_class): void
    {
        $this->cell_class = $cell_class;
    }

}
