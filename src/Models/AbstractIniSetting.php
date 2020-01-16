<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use Noodlehaus\Config;
use SP\Admin\Traits\ModelLabels;

/**
 * Base class for settings in INI file.
 *
 * @package SP\Admin\Models
 */
abstract class AbstractIniSetting implements \ArrayAccess
{
    use ModelLabels;

    /**
     * @var string
     */
    protected string $file_path;
    /**
     * @var Config
     */
    protected Config $ini;

    /**
     * SettingBasic constructor.
     */
    public function __construct()
    {
        $this->file_path = database_path($this->fileName());
        $this->createFile();

        $this->ini = Config::load($this->file_path);
    }

    /**
     * Filename without path.
     *
     * @return string
     */
    abstract public function fileName(): string;

    /**
     * Section name for ini file.
     *
     * @return string
     */
    abstract protected function sectionName(): string;

    /**
     * Creates ini file.
     */
    private function createFile(): void
    {
        if (!\file_exists($this->file_path)) {
            $f = \fopen($this->file_path, 'cb');
            \fwrite($f, '[' . $this->sectionName() . ']');
            \fclose($f);
        }
    }

    /**
     * Gets setting value by name.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        $section = $this->sectionName();

        return $this->ini->get("$section.$key", $default);
    }

    /**
     * Sets setting value.
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $section = $this->sectionName();

        $this->ini->set("$section.$key", $value);
        $this->ini->toFile($this->file_path);
    }

    /**
     * Gets all settings.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->ini->all()[$this->sectionName()];
    }

    /**
     * Sets all settings at ones.
     *
     * @param array $settings
     */
    public function setAll(array $settings): void
    {
        $this->ini[$this->sectionName()] = $settings;
        $this->ini->toFile($this->file_path);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->ini->has($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): void
    {
        unset(
            $this->ini[$this->sectionName()][$offset]
        );
    }

}
