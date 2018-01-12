<?php


namespace Intranet\Contracts\Repositories;


/**
 * Interface for login repository.
 */
interface LoginRepositoryInterface
{
    /**
     * Find login by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id);

    /**
     * Find login by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username);

    /**
     * Save login to database.
     * @param array $login
     * @return bool
     */
    public function save(array $login);

    /**
     * Delete the login from the database.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Delete all logins older than $days.
     * @param int $days
     * @return int Number of deleted logins
     */
    public function deleteOlder(int $days): int;
}