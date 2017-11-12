<?php


namespace Core\Contracts\Session;


/**
 * Interface for interaction with session variables.
 * @package Core\Contracts\Session\
 */
interface SessionManagerInterface
{
    /**
     * Set the specified session variable.
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

    /**
     * Get the value of the specified session variable.
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key);

    /**
     * Check if the session variable is set.
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Unset specified session variable.
     * @param string $key
     */
    public function unset(string $key);

    /**
     * Get session id.
     * @return string
     */
    public function id(): string;
}