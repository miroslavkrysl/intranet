<?php


namespace Intranet\Contracts\Repositories;


interface CarRepositoryInterface
{
    /**
     * Find car by name.
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name);

    /**
     * Find all cars.
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAll(array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array;

    /**
     * Save car to database.
     * @param array $car
     * @return array
     */
    public function save(array $car): array;

    /**
     * Delete the car from the database.
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool;
}