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
     * Find login by userId.
     * @param int $userId
     * @return array|null
     */
    public function findByUserId(int $userId);

    /**
     * Save login to database.
     * @param array $login
     * @return mixed
     */
    public function save(array $login);

    /**
     * Delete the login from the database.
     * @param int $loginId
     * @return int
     */
    public function delete(int $loginId): bool;

    /**
     * Delete all logins older than $days.
     * @param int $days
     */
    public function deleteOlder(int $days);
}