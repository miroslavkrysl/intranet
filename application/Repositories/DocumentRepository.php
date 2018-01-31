<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\DocumentRepositoryInterface;

class DocumentRepository implements DocumentRepositoryInterface
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
     * DocumentRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Find document by id.
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
     * Find documents by username.
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
            "WHERE user_username = :username ".
            ($orderBy == null ? "" : "ORDER BY " . \implode(", ", $orderBy) . " ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT $limit ").
            ($offset == null ? "" : "OFFSET $offset ").
            ";";

        $params = [
            'username' => $username
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Find all documents.
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
     * Save document to database.
     * @param array $document
     * @return boolean
     */
    public function save(array $document): bool
    {
        $query =
            "INSERT INTO $this->table ".
            "(id, user_username, name, src) ".
            "VALUES ".
            "(:id, :user_username, :name, :src) ".
            "ON DUPLICATE KEY UPDATE ".
            "user_username = :user_username1, ".
            "name = :name1, ".
            "src = :src1;";

        $params = [
            'id' => $document['id'] ?? null,
            'user_username' => $document['user_username'],
            'name' => $document['name'],
            'src' => $document['src'],
            'user_username1' => $document['user_username'],
            'name1' => $document['name'],
            'src1' => $document['src']
        ];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete the document from the database.
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