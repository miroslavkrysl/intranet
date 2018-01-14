<?php


namespace Intranet\Contracts\Repositories;


/**
 * Interface for login repository.
 */
interface LoginRepositoryInterface
{
    /**
     * Find login by id.
     * @param string $id
     * @return array|null
     */
    public function findById(string $id);

    /**
     * Find all logins by username.
     * @param string $username
     * @return array
     */
    public function findByUsername(string $username): array;

    /**
     * Save login to database.
     * @param array $login
     * @return bool
     */
    public function save(array $login): bool;

    /**
     * Delete the login from the database.
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Delete all logins older than $days.
     * @param int $days
     * @return int Number of deleted logins
     */
    public function deleteOlder(int $days): int;

    /**
     * Hash the login token.
     * @param string $token
     * @return string Hash.
     */
    public function hashToken(string $token): string;

    /**
     * Verify the login token..
     * @param string $token
     * @param string $hash
     * @return bool
     */
    public function verifyToken(string $token, string $hash): bool;
}