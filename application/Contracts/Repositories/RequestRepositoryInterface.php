<?php


namespace Intranet\Contracts\Repositories;


interface RequestRepositoryInterface
{
    /**
     * Find request by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id);

    /**
     * Find requests by car name.
     * @param string $name
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findByCarName(int $name, int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array;

    /**
     * Find requests by driver username.
     * @param string $username
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findByDriverUsername(string $username, int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array ;

    /**
     * Find requests where reserved from is after the given datetime.
     * @param string $datetime
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findNewerThan(string $datetime, int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array;

    /**
     * Save request to database.
     * @param array $request
     * @return bool
     */
    public function save(array $request): bool;

    /**
     * Delete the request from the database.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}