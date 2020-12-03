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
     * @var Config
     */
    protected Config $ini;

    /**
     * SettingBasic constructor.
     */
    public function __construct()
    {
        $this->createFile();

        $this->ini = Config::load($this->filePath());
    }

    /**
     * Filename without path.
     *
     * @return string
     */
    abstract public function filePath(): string;

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
        if (!file_exists($this->filePath())) {
            $f = fopen($this->filePath(), 'cb');
            fwrite($f, '[' . $this->sectionName() . ']');
            fclose($f);
        }
    }

    /**
     * Gets setting value by name.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $section = $this->sectionName();

        return $this->ini->get("$section.$key", $default);
    }

    /**
     * Sets setting value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        $section = $this->sectionName();

        $this->ini->set("$section.$key", $value);
        $this->ini->toFile($this->filePath());
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
        $this->ini->toFile($this->filePath());
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
