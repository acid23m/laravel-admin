<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

/**
 * Column for model field.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class ModelColumn extends Column
{
    /**
     * @var string
     */
    protected string $attribute;
    /**
     * @var bool|array|\Closure
     */
    protected $filter;

    /**
     * @return string
     * @throws \Throwable
     */
    public function getAttribute(): string
    {
        throw_if(
            $this->attribute === null,
            \InvalidArgumentException::class,
            'The attribute is not set.'
        );

        return $this->attribute;
    }

    /**
     * @param string $attribute
     */
    public function setAttribute(string $attribute): void
    {
        $this->attribute = $attribute;
    }

    /**
     * @return array|bool|\Closure
     * @throws \Throwable
     */
    public function getFilter()
    {
        if ($this->filter === null) {
            $this->filter = false;
        }

        return $this->filter;
    }

    /**
     * Column filter.
     *
     * @param array|bool|\Closure $filter
     * Possible values are:
     * - false : the filter will not render.
     * - true : the text input will render.
     * - array : the select box will render. First empty option will add automatically if it not exists.
     * - function : The definition is `function (string $filter_input_class): string {}`.
     */
    public function setFilter($filter): void
    {
        $this->filter = $filter;
    }

}
