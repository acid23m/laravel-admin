<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelDetails\Rows;

/**
 * Row for model field.
 *
 * @package SP\Admin\Widgets\ModelDetails\Rows
 */
class ModelRow extends Row
{
    /**
     * @var string
     */
    protected string $attribute;

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

}
