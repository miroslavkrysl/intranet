<?php


namespace Core\Contracts\Config;


interface ConfigInterface
{
    /**
     * Set the specified setting.
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, mixed $value);

    /**
     * Get the specified setting.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if the setting exists.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
}