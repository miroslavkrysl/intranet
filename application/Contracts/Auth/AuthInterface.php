<?php


namespace Intranet\Contracts\Auth;


interface AuthInterface
{
    /**
     * Login the user.
     * @param $username
     * @param bool $remember
     */
    public function login($username, bool $remember = false);

    /**
     * Logout the user.
     */
    public function logout();

    /**
     * Check whether the user is logged.
     * @return bool
     */
    public function isLogged():bool;

    /**
     * Get the logged user.
     * @return array|null
     */
    public function user();

    /**
     * Delete all logins older than $days.
     * @param int|null $days
     */
    public function deleteOldLogins(int $days = null);
}