<?php


namespace Intranet\Contracts\Repositories;


/**
 * Interface for user repository.
 */
interface UserRepositoryInterface
{
    /**
     * Find user by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id);

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
     * @param int $userId
     * @return int
     */
    public function delete(int $userId): bool;
}