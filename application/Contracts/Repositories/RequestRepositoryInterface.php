<?php


namespace Intranet\Contracts\Repositories;


interface RequestRepositoryInterface
{
    /**
     * Create new empty request represented as an array.
     * @return array
     */
    public function create(): array;

    /**
     * Find request by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id);

    /**
     * Find requests by username.
     * @param string $username
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByUsername(string $username, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array ;


    /**
     * Find requests by car name.
     * @param string $name
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByCarName(int $name, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array;

    /**
     * Find requests by driver username.
     * @param string $username
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByDriverUsername(string $username, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array ;

    /**
     * Find requests where reserved from or to is between the given datetime.
     * @param string $from
     * @param string $to
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findReservedFromBetween(string $from, string $to, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array;

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