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
     * @var string
     */
    private $userCanDriveTable;
    /**
     * @var string
     */
    private $userTable;

    /**
     * CarRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     * @param string $userCanDriveTable
     * @param string $userTable
     */
    public function __construct(DatabaseInterface $database, string $table, string $userCanDriveTable, string $userTable)
    {
        $this->database = $database;
        $this->table = $table;
        $this->userCanDriveTable = $userCanDriveTable;
        $this->userTable = $userTable;
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
            ($orderBy == null ? "" : "ORDER BY " . \implode(", ", $orderBy) . " ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT $limit ").
            ($offset == null ? "" : "OFFSET $offset ").
            ";";

        $this->database->execute($query);
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
            "manufacturer = :manufacturer1, ".
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

    /**
     * Find all users, that can drive the given car.
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findUsersCanDrive(string $carName, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        $query =
            "SELECT u.* ".
            "FROM $this->table AS `c`".
            "JOIN $this->userCanDriveTable as `ucd` ".
                "ON c.name = ucd.car_name ".
            "JOIN $this->userTable as `u` ".
                "ON ucd.user_username = u.username " .
            "WHERE c.name = :name ".
            ($orderBy == null ? "" : "ORDER BY " . \implode(", ", $orderBy) . " ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT $limit ").
            ($offset == null ? "" : "OFFSET $offset ").
            ";";

        $params = [
            'name' => $carName
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Save the user_can_drive relation to the database.
     * @param string $carName
     * @param string $username
     * @return array
     */
    public function saveUserCanDrive(string $carName, string $username): array
    {
        $query =
            "INSERT INTO $this->userCanDriveTable ".
            "(car_name, user_username) ".
            "VALUES ".
            "(:car_name, :user_username) ".
            "ON DUPLICATE KEY UPDATE ".
            "car_name = :car_name1, ".
            "user_username = :user_username1;";

        $params = [
            'car_name' => $carName,
            'user_username' => $username,
            'car_name1' => $carName,
            'user_username1' => $username
        ];

        $this->database->execute($query, $params);

        return ['car_name' => $carName, 'user_username' => $username];
    }

    /**
     * Delete the user_can_drive relation from the database.
     * @param string $carName
     * @param string $username
     * @return bool
     */
    public function deleteUserCanDrive(string $carName, string $username): bool
    {
        $query =
            "DELETE FROM $this->userCanDriveTable ".
            "WHERE car_name = :car_name AND user_username = :username;";
        $params = [
            'car_name' => $carName,
            'username' => $username
        ];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }
}