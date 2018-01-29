<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\RequestRepositoryInterface;

class RequestRepository implements RequestRepositoryInterface
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
     * RequestRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Create new empty request represented as an array.
     * @return array
     */
    public function create(): array
    {
        return array(
            'id' => null,
            'user_username' => null,
            'car_name' => null,
            'reserved_from' => null,
            'reserved_to' => null,
            'driver_username' => null,
            'purpose' => null,
            'destination' => null,
            'passengers' => null,
            'confirmed' => false,
        );
    }

    /**
     * Find request by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE id = :id;";

        $params = ['id' => $id];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Find requests by username.
     * @param string $username
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByUsername(string $username, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE user_username = :user_username ".
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";
        \var_dump($query);

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset,
            'user_username' => $username
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Find requests by car name.
     * @param string $name
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByCarName(int $name, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE car_name = :car_name ".
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";
        \var_dump($query);

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset,
            'car_name' => $name
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Find requests by driver username.
     * @param string $username
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByDriverUsername(string $username, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE driver_username = :driver_username ".
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";
        \var_dump($query);

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset,
            'driver_username' => $username
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Find requests where reserved from or to is between the given datetimes.
     * @param string $from
     * @param string $to
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findReservedFromBetween(string $from, string $to, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array
    {
        if (\is_null($orderBy)) {
            $orderBy = ['reserved_from'];
        }

        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE reserved_from BETWEEN :from AND :to ".
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset,
            'from' => $from,
            'to' => $to
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Save request to database.
     * @param array $request
     * @return bool
     */
    public function save(array $request): bool
    {
        $query =
            "INSERT INTO $this->table ".
            "(id, user_username, car_name, reserved_from, reserved_to, driver_username, purpose, destination, passengers, confirmed) ".
            "VALUES ".
            "(:id, :user_username, :car_name, :reserved_from, :reserved_to, :driver_username, :purpose, :destination, :passengers, :confirmed) ".
            "ON DUPLICATE KEY UPDATE ".
            "user_username = :user_username, ".
            "car_name = :car_name1, ".
            "reserved_from = :reserved_from1, ".
            "reserved_to = :reserved_to1, ".
            "driver_username = :driver_username1, ".
            "purpose = :purpose1, ".
            "destination = :destination1, ".
            "passengers = :passengers1, ".
            "confirmed = :confirmed1;";

        $params = [
            'id' => $request['id'] ?? null,
            'user_username' => $request['user_username'],
            'car_name' => $request['car_name'],
            'reserved_from' => $request['reserved_from'],
            'reserved_to' => $request['reserved_to'],
            'driver_username' => $request['driver_username'],
            'purpose' => $request['purpose'],
            'destination' => $request['destination'],
            'passengers' => $request['passengers'],
            'confirmed' => $request['confirmed'],
            'user_username1' => $request['user_username'],
            'car_name1' => $request['car_name'],
            'reserved_from1' => $request['reserved_from'],
            'reserved_to1' => $request['reserved_to'],
            'driver_username1' => $request['driver_username'],
            'purpose1' => $request['purpose'],
            'destination1' => $request['destination'],
            'passengers1' => $request['passengers'],
            'confirmed1' => $request['confirmed']
        ];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete the request from the database.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $id];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }
}