<?php


namespace Core\Contracts\Config;


interface ConfigInterface
{
    /**
     * Set the specified setting.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * Get the specified setting.
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Check if the setting exists.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
}