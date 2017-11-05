<?php


namespace Core\Session;


use Core\Contracts\Session\SessionManagerInterface;
use Core\Session\Exception\SessionVariableNotExistsException;


/**
 * Implementation of SessionManagerInterface for interaction with session variables.
 */
class SessionManager implements SessionManagerInterface
{

    /**
     * Set the specified session variable.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get the value of the specified session variable.
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (!$this->isset($key)) {
            throw new SessionVariableNotExistsException;
        }
        return $_SESSION[$key];
    }

    /**
     * Check if the session variable is set.
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Unset specified session variable.
     * @param string $key
     */
    public function unset(string $key)
    {
        if (!$this->isset($key)) {
            throw new SessionVariableNotExistsException();
        }
        $this->unset($_SESSION[$key]);
    }
}