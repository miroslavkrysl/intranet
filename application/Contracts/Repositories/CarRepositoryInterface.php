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
     * Save car to database.
     * @param array $car
     * @return boolean
     */
    public function save(array $car): bool;

    /**
     * Delete the car from the database.
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool;

    /**
     * Find all cars.
     * @param int $limit
     * @param int $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findAll(int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array;
}