<?php


namespace Core\Contracts\Cookies;


/**
 * Interface for interaction with cookies.
 */
interface CookiesManagerInterface
{
    /**
     * Set the specified cookie.
     * @param string $key
     * @param mixed $value
     * @param int $expire Cookie expiration timestamp
     */
    public function set(string $key, mixed $value, int $expire);

    /**
     * Get the value of the specified cookie.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if the cookie is set.
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool;

    /**
     * Unset specified cookie.
     * @param string $key
     */
    public function unset(string $key);
}