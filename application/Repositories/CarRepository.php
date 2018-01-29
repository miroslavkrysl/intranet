<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\CarRepositoryInterface;

class CarRepository implements CarRepositoryInterface
{
    /**
     * The database.
     * @var DatabaseInterface
     */
    private $database;

    /**
     * Table name.
     * @var string
     */
    private $table;

    /**
     * CarRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Find car by name.
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE name = :name;";

        $params = ['name' => $name];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Find all cars.
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAll(array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Save car to database.
     * @param array $car
     * @return array
     */
    public function save(array $car): array
    {
        $query =
            "INSERT INTO $this->table ".
            "(name, description, manufacturer, model) ".
            "VALUES ".
            "(:name, :description, :manufacturer, :model) ".
            "ON DUPLICATE KEY UPDATE ".
            "description = :description1, ".
            "manifacturer = :manifacturer1, ".
            "model = :model1;";

        $params = [
            'name' => $car['name'] ?? null,
            'description' => $car['description'],
            'manufacturer' => $car['manufacturer'],
            'model' => $car['model'],
            'description1' => $car['description'],
            'manufacturer1' => $car['manufacturer'],
            'model1' => $car['model']
        ];

        $this->database->execute($query, $params);

        return $car;
    }

    /**
     * Delete the car from the database.
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE name = :name;";
        $params = ['name' => $name];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }
}