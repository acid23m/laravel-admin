<?php
declare(strict_types=1);

namespace SP\Admin\Traits;

use Illuminate\Support\Str;

/**
 * Attribute labels.
 *
 * @package SP\Admin\Traits
 */
trait ModelLabels
{
    /**
     * List of labels.
     * [attribute name => description]
     *
     * @static
     * @return array
     */
    public static function attributeLabels(): array
    {
        return [];
    }

    /**
     * Shows label for the given attribute.
     *
     * @static
     * @param string $attribute Model attribute
     * @return string
     */
    public static function getAttributeLabel(string $attribute): string
    {
        $labels = static::attributeLabels();

        if (!\array_key_exists($attribute, $labels)) {
            return Str::title(
                \str_replace('_', ' ', Str::snake($attribute))
            );
        }

        return $labels[$attribute];
    }

}
