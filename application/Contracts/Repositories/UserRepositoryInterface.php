<?php


namespace Intranet\Contracts;


use Intranet\Models\User;


/**
 * Interface for user repository.
 */
interface UserRepositoryInterface
{
    /**
     * Find user by id.
     * @param int $id
     * @return User|null
     */
    public function findById(int $id);

    /**
     * Find user by username.
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username);

    /**
     * Find user by email.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email);

    /**
     * Validate the user and set validation failures eventually.
     * @param User $user
     * @return bool
     */
    public function validate(User $user): bool;

    /**
     * Get validation failures messages.
     * @return array|null
     */
    public function getValidationFailures(): array;

    /**
     * Save user to database.
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool;

    /**
     * Delete the user from database.
     * @param User $user
     * @return int
     */
    public function delete(User $user): bool;

    /**
     * Determine if the user exists.
     * @param User $user
     * @return bool
     */
    public function exists(User $user): bool;
}