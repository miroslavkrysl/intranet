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
     * Find login by userId.
     * @param int $userId
     * @return array|null
     */
    public function findByUserId(int $userId)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE user_id = :user_id;";
        $params = ['user_id' => $userId];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Save login to database.
     * @param array $login
     * @return mixed
     */
    public function save(array $login)
    {
        if (isset($login['id'])) {
            $query =
                "UPDATE $this->table ".
                "SET ".
                "token = :token, ".
                "user_id = :user_id ".
                "WHERE id = :id;";
            $params = [
                'token' => $login['token'],
                'user_id' => $login['user_id'],
                'id' => $login['id']
            ];
        }
        else {
            $query =
                "INSERT INTO $this->table ".
                "(token, user_id) ".
                "VALUES ".
                "(:token, :user_id);";
            $params = [
                'token' => $login['token'],
                'user_id' => $login['user_id']
            ];
        }

        $this->database->execute($query, $params);

        return $this->database->lastInsertedId();
    }

    /**
     * Delete the login from the database.
     * @param int $loginId
     * @return int
     */
    public function delete(int $loginId): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $loginId];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete all logins older than $days.
     * @param int $days
     */
    public function deleteOlder(int $days)
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE updated_at <= (NOW() - INTERVAL $days DAY);";

        $this->database->execute($query);
    }
}