<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\LoginRepositoryInterface;

class LoginRepository implements LoginRepositoryInterface
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
     * LoginRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Find login by id.
     * @param string $id
     * @return array|null
     */
    public function findById(string $id)
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
     * Find all logins by username.
     * @param string $username
     * @return array
     */
    public function findByUsername(string $username): array
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE user_username = :user_username;";
        $params = ['user_username' => $username];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? null;
    }

    /**
     * Save login to database.
     * @param array $login
     * @return bool
     */
    public function save(array $login): bool
    {
        $query =
            "INSERT INTO $this->table ".
            "(id, token, user_username) ".
            "VALUES ".
            "(:id, :token, :user_username) ".
            "ON DUPLICATE KEY UPDATE ".
            "token = :token1, ".
            "user_username = :user_username1;";
        $params = [
            'id' => $login['id'],
            'token' => $login['token'],
            'user_username' => $login['user_username'],
            'token1' => $login['token'],
            'user_username1' => $login['user_username']
        ];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete the login from the database.
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $id];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete all logins older than $days.
     * @param int $days
     * @return int Number of deleted logins
     */
    public function deleteOlder(int $days): int
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE updated_at <= (NOW() - INTERVAL $days DAY);";

        $this->database->execute($query);
        return $this->database->count();
    }

    /**
     * Hash the login token.
     * @param string $token
     * @return string Hash.
     */
    public function hashToken(string $token): string
    {
        return \password_hash($token, \PASSWORD_DEFAULT);
    }

    /**
     * Verify the login token..
     * @param string $token
     * @param string $hash
     * @return bool
     */
    public function verifyToken(string $token, string $hash): bool
    {
        return \password_verify($token, $hash);
    }
}