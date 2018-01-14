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
    public function findPermissions(string $username): array;

    /**
     * Check whether the user can manage users.
     * @param string $username
     * @return bool
     */
    public function canManageUsers(string $username): bool;

    /**
     * Check whether the user can manage documents.
     * @param string $username
     * @return bool
     */
    public function canManageDocuments(string $username): bool;

    /**
     * Check whether the user can manage own documents.
     * @param string $username
     * @return bool
     */
    public function canManageOwnDocuments(string $username): bool;

    /**
     * Check whether the user can manage requests.
     * @param string $username
     * @return bool
     */
    public function canManageRequests(string $username): bool;

    /**
     * Check whether the user can manage own requests.
     * @param string $username
     * @return bool
     */
    public function canManageOwnRequests(string $username): bool;

    /**
     * Check whether the user can confirm requests.
     * @param string $username
     * @return bool
     */
    public function canConfirmRequests(string $username): bool;
}