<?php


namespace Intranet\Contracts\Repositories;


/**
 * Interface for user repository.
 */
interface UserRepositoryInterface
{
    /**
     * Remove sensitive data from user array.
     * @param array $user
     * @return array
     */
    public function toPublic(array $user): array;

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
     * Find all users.
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAll(array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array;

    /**
     * Save user to database.
     * @param array $user
     * @return array User
     */
    public function save(array $user): array;

    /**
     * Delete the user from database.
     * @param string $username
     * @return bool
     */
    public function delete(string $username): bool;

    /**
     * Hash the user's password.
     * @param string $password
     * @return string Hash.
     */
    public function hashPassword(string $password): string;

    /**
     * Verify the user's password.
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool;

    /**
     * Find all permissions that has the given user.
     * @param string $username
     * @return array
     */
    public function findPermissionsNames(string $username): array;

    /**
     * Check whether the user has the permission.
     * @param string $username
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $username, string $permission): bool;

    /**
     * Check whether the user can drive the given car.
     * @param string $username
     * @param string $carName
     * @return bool
     */
    public function canDrive(string $username, string $carName): bool;
}