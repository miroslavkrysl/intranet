<?php


namespace Core;


use Core\Database\DatabaseInterface;

/**
 * Base Repository.
 */
abstract class Repository
{
    /**
     * A connection to a database.
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * Name of the associated table in the database.
     * @var string
     */
    protected $table;

    /**
     * Repository constructor.
     * @param DatabaseInterface $connection
     * @param string $table
     */
    public function __construct(DatabaseInterface $connection, string $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }
}