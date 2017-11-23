<?php


namespace Core\Contracts\Database;


/**
 * Determines the interface for interaction with the database.
 */
interface DatabaseInterface
{
    /**
     * Execute query with params.
     * @param string $query SQL query with named parameters.
     * @param array $params Associative array with parameters.
     * @return bool True on success, false on failure.
     */
    public function execute(string $query, array $params = []): bool;

    /**
     * Get first row from last result set as an array indexed by column name.
     * @return array|false Associative array containing result row. False on failure
     */
    public function fetch();

    /**
     * Get all rows from last result set as an array indexed by row number and then by column name.
     * @return array Associative array containing result rows
     */
    public function fetchAll(): array;

    /**
     * Returns the number of rows affected by the last executed DELETE, INSERT, or UPDATE statement.
     * @return int Number of affected rows
     */
    public function count(): int;

    /**
     * Get id of the last inserted record.
     * @return mixed
     */
    public function lastInsertId();
}