<?php


namespace Intranet\Contracts\Repositories;


/**
 * Interface for user repository.
 */
interface UserRepositoryInterface
{
    /**
     * Find user by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username);

    /**
     * Find user by email.
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email);

    /**
     * Save user to database.
     * @param array $user
     * @return bool
     */
    public function save(array $user): bool;

    /**
     * Delete the user from database.
     * @param string $username
     * @return bool
     */
    public function delete(string $username): bool;

    /**
     * Find all users.
     * @param int $limit
     * @param int $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findAll(int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array;

    /**
     * Verify the user's password.
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool;
}