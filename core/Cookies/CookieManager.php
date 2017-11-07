<?php


namespace Core\Cookies;


use Core\Contracts\Cookies\CookieManagerInterface;
use Core\Cookies\Exception\CookieNotExistsException;


/**
 * Implementation of CookiesInterface
 */
class CookieManager implements CookieManagerInterface
{

    /**
     * Set the specified cookie.
     * @param string $key
     * @param mixed $value
     * @param int $expire Cookie expiration timestamp
     */
    public function set(string $key, $value)
    {
        $conf = \config('cookie');
        \setcookie($key, $value, \time() + $conf['expire'], $conf['path'], $conf['domain']);
    }

    /**
     * Get the value of the specified cookie.
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->isset($key)) {
            throw new CookieNotExistsException('Cookie ' . $key . ' does not exist.');
        }
        return $_COOKIE[$key];
    }

    /**
     * Check if the cookie is set.
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        return $this->isset($_COOKIE[$key]);
    }

    /**
     * Unset specified cookie.
     * @param string $key
     */
    public function unset(string $key)
    {
        $this->unset($_COOKIE[$key]);
    }
}