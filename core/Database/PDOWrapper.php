<?php


namespace Core\Database;


use Core\Contracts\Database\DatabaseInterface;


/**
 * Simple PDO wrapper.
 */
class PDOWrapper implements DatabaseInterface
{
    /**
     * PDO connection to database.
     * @var \PDO
     */
    private $connection;

    /**
     * Type of the DBMS.
     * @var string
     */
    private $type;

    /**
     * Database host.
     * @var string
     */
    private $host;

    /**
     * Database name.
     * @var string
     */
    private $database;

    /**
     * Username used for connecting.
     * @var string
     */
    private $username;

    /**
     * Password used for connecting.
     * @var string
     */
    private $password;

    /**
     * PDOStatement with query results.
     * @var \PDOStatement
     */
    private $statement;

    /**
     * PDO settings.
     * @var array
     */
    private $settings;

    /**
     * PDOWrapper constructor.
     * @param string $type
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct(string $type, string $host, string $database, string $username, string $password)
    {
        $this->type = $type;
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->settings = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );

        $this->connection = new \PDO(
            "$this->type:dbname=$this->database;host=$this->host",
            $this->username,
            $this->password,
            $this->settings
        );
    }

    /**
     * Execute query with params.
     * @param string $query SQL query with named parameters.
     * @param array $params Associative array with parameters.
     * @return self
     */
    public function execute(string $query, array $params = []): self
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    /**
     * Get first row from last result set as an array indexed by column name.
     * @return array Associative array containing result row
     */
    public function fetch(): array
    {
        return $this->statement ? $this->statement->fetch() : null;
    }

    /**
     * Get all rows from last result set as an array indexed by row number and then by column name.
     * @return array Associative array containing result rows
     */
    public function fetchAll(): array
    {
        return $this->statement ? $this->statement->fetchAll() : null;
    }
    
    /**
     * Returns the number of rows affected by the last executed DELETE, INSERT, or UPDATE statement.
     * @return int Number of affected rows
     */
    public function count(): int
    {
        return $this->statement ? $this->statement->rowCount() : 0;
    }
}
