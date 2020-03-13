<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelDetails\Rows;

/**
 * Row for grid.
 *
 * @package SP\Admin\View\Widgets\ModelDetails\Rows
 */
class Row
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
     * Initializes row.
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
     * @return \Closure|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \Closure|string|null $value
     * Possible values are:
     * - null : the grid will try to get value from model by attribute.
     * - string : this string will render.
     * - function : the definition is `function (Model $item): string {}`.
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

}
