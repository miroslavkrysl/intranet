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
     * SessionManager constructor. Starts a session.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

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
        return $_SESSION[$key] ?? null;
    }

    /**
     * Check if the session variable is set.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Unset specified session variable.
     * @param string $key
     */
    public function unset(string $key)
    {
        unset($_SESSION[$key]);
    }


    /**
     * Get session id.
     * @return string
     */
    public function id(): string
    {
        return \session_id();
    }
}