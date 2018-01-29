<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
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
     * Find all roles.
     * @return array
     */
    public function findAll(): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table;";

        $this->database->execute($query);
        return $this->database->fetchAll() ?? [];
    }
}