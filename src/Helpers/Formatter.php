<?php
declare(strict_types=1);

namespace SP\Admin\Helpers;

use Illuminate\Support\Carbon;

/**
 * Formatter.
 *
 * @package SP\Admin\Helpers
 */
class Formatter
{
    /**
     * Formats standard datetime to localized human-readable value.
     *
     * @static
     * @param string|Carbon $datetime "Y-m-d H:i:s" string or Carbon instance
     * @return string
     */
    public static function isoToLocalDateTime($datetime): string
    {
        /** @var \Carbon\Carbon $dt */
        $dt = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime, 'UTC');

        /** @var string $timezone */
        $timezone = config('app.timezone', 'UTC');
        /** @var string $locale */
        $locale = config('app.locale', 'en');

        return $dt
            ->timezone($timezone)
            ->locale($locale)
            ->isoFormat('lll');
    }

    /**
     * Formats standard date to localized human-readable value.
     *
     * @static
     * @param string|Carbon $datetime "Y-m-d H:i:s" string or Carbon instance
     * @return string
     */
    public static function isoToLocalDate($datetime): string
    {
        /** @var \Carbon\Carbon $dt */
        $dt = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime, 'UTC');

        /** @var string $timezone */
        $timezone = config('app.timezone', 'UTC');
        /** @var string $locale */
        $locale = config('app.locale', 'en');

        return $dt
            ->timezone($timezone)
            ->locale($locale)
            ->isoFormat('ll');
    }

    /**
     * Formats boolean value to string.
     *
     * @static
     * @param mixed $value
     * @return string
     */
    public static function booleanToString($value): string
    {
        return (bool)$value ? trans('Yes') : trans('No');
    }

    /**
     * Displays bytes in human readable format.
     *
     * @static
     * @param int|float|string $size
     * @param array|null $options
     * @return string
     */
    public static function byteSize($size, ?array $options = null): string
    {
        $o = [
            'binary' => false,
            'decimalPlaces' => 2,
            'decimalSeparator' => '.',
            'thousandsSeparator' => '',
            'maxThreshold' => false, // or thresholds key
            'suffix' => [
                'thresholds' => ['', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
                'decimal' => ' {threshold}b',
                'binary' => ' {threshold}iB',
                'bytes' => ' B',
            ],
        ];

        if ($options !== null) {
            $o = array_replace_recursive($o, $options);
        }

        $base = $o['binary'] ? 1024 : 1000;
        $exp = $size ? \floor(\log($size) / \log($base)) : 0;

        if (($o['maxThreshold'] !== false) &&
            ($o['maxThreshold'] < $exp)
        ) {
            $exp = $o['maxThreshold'];
        }

        return !$exp
            ? (\round($size) . $o['suffix']['bytes'])
            : (
                \number_format(
                    $size / ($base ** $exp),
                    $o['decimalPlaces'],
                    $o['decimalSeparator'],
                    $o['thousandsSeparator']
                ) .
                \str_replace(
                    '{threshold}',
                    $o['suffix']['thresholds'][$exp],
                    $o['suffix'][$o['binary'] ? 'binary' : 'decimal']
                )
            );
    }

}
