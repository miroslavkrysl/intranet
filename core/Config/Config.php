<?php


namespace Core\Config;


use Core\Config\Exception\SettingNotExistsException;
use Core\Contracts\Config\ConfigInterface;
use Core\DotArray\DotArray;


/**
 * Implementation of ConfigInterface.
 */
class Config implements ConfigInterface
{
    /**
     * DotArray with settings.
     * @var DotArray
     */
    private $settings;

    /**
     * Config constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = new DotArray($settings);
    }

    /**
     * Set the specified setting.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->settings->set($key, $value);
    }

    /**
     * Get the specified setting.
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            throw new SettingNotExistsException('Setting ' . $key . ' does not exist.');
        }
        return $this->settings->get($key);
    }

    /**
     * Check if the setting exists.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->settings->has($key);
    }
}