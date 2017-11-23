<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;
use Intranet\Repositories\Exception\RepositoryException;


class UserRepository implements UserRepositoryInterface
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
     * UserRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Find user by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE id = :id ".
            "AND deleted_at IS NULL;";
        $params = ['id' => $id];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Find user by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE username = :username ".
            "AND deleted_at IS NULL;";
        $params = ['username' => $username];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Find user by email.
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE email = :email ".
            "AND deleted_at IS NULL;";
        $params = ['email' => $email];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Save user to database.
     * @param array $user
     * @return bool
     */
    public function save(array $user): bool
    {
        if (isset($user['id'])) {
            $query =
                "UPDATE $this->table ".
                "SET ".
                "username = :username, ".
                "password = :password, ".
                "name = :name, ".
                "email = :email, ".
                "deleted_at = :deleted_at ".
                "WHERE id = :id;";
            $params = [
                'username' => $user['username'],
                'password' => $user['password'],
                'name' => $user['name'],
                'email' => $user['email'],
                'deleted_at' => $user['deleted_at'],
                'id' => $user['id']
            ];
        }
        else {
            $query =
                "INSERT INTO $this->table ".
                "(username, password, name, email, deleted_at) ".
                "VALUES ".
                "(:username, :password, :name, :email, :deleted_at);";
            $params = [
                'username' => $user['username'],
                'password' => $user['password'],
                'name' => $user['name'],
                'email' => $user['email'],
                'deleted_at' => $user['deleted_at']
            ];
        }

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Delete the user from database.
     * @param int $userId
     * @return int
     */
    public function delete(int $userId): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $userId];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Verify the user's password.
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }
}