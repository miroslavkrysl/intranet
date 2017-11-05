<?php


namespace Core\Cookies;


use Core\Contracts\Cookies\CookiesManagerInterface;


/**
 * Implementation of CookiesInterface
 */
class CookiesManager implements CookiesManagerInterface
{

    /**
     * Set the specified cookie.
     * @param string $key
     * @param mixed $value
     * @param int $expire Cookie expiration timestamp
     */
    public function set(string $key, mixed $value, int $expire)
    {
        \config()
        \setcookie($key, $value, $expire, null, );
    }

    /**
     * Get the value of the specified cookie.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        // TODO: Implement get() method.
    }

    /**
     * Check if the cookie is set.
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        // TODO: Implement isset() method.
    }

    /**
     * Unset specified cookie.
     * @param string $key
     */
    public function unset(string $key)
    {
        // TODO: Implement unset() method.
    }
}