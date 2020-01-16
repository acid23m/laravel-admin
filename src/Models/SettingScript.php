<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use SP\Admin\Traits\ModelLabels;

/**
 * Custom client scripts.
 *
 * @package SP\Admin\Models
 */
final class SettingScript
{
    use ModelLabels;

    public const HEAD = 'head';
    public const BOTTOM = 'bottom';

    /**
     * SettingScript constructor.
     */
    public function __construct()
    {
        $this->createFile($this->getPath(self::HEAD));
        $this->createFile($this->getPath(self::BOTTOM));
    }

    /**
     * Creates js file.
     *
     * @param string $path
     */
    private function createFile(string $path): void
    {
        if (!\file_exists($path)) {
            $f = \fopen($path, 'cb');
            \fwrite($f, '');
            \fclose($f);
        }
    }

    /**
     * Path to js file.
     *
     * @param string $position
     * @return string
     */
    public function getPath(string $position): string
    {
        return public_path("js/$position.js");
    }

    /**
     * Url to js file.
     *
     * @param string $position
     * @return string
     */
    public function getUrl(string $position): string
    {
        return asset("js/$position.js");
    }

    /**
     * Gets js file content.
     *
     * @param string $position
     * @return string
     * @throws \InvalidArgumentException
     */
    public function get(string $position): string
    {
        $content = '';

        $f = \fopen($this->getPath($position), 'rb');
        while (!\feof($f)) {
            $content .= \fread($f, 8192);
        }
        \fclose($f);

        return $content;
    }

    /**
     * Save content to js file.
     *
     * @param string $position
     * @param string|null $content
     */
    public function set(string $position, ?string $content): void
    {
        $f = \fopen($this->getPath($position), 'wb');
        \fwrite($f, (string)$content);
        \fclose($f);
    }

    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        return [
            self::HEAD => trans('Section <head> of the Page'),
            self::BOTTOM => trans('At the bottom of the Page before </body>'),
        ];
    }

}
