<?php


namespace Core\Contracts\Cookies;


/**
 * Interface for interaction with cookies.
 */
interface CookieManagerInterface
{
    /**
     * Set the specified cookie.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * Get the value of the specified cookie.
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

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