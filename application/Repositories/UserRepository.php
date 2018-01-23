<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\Repositories\UserRepositoryInterface;

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
     * Name of the rolePermission table.
     * @var string
     */
    private $rolePermissionTable;
    /**
     * Name of the rolePermission table.
     * @var string
     */
    private $permissionTable;

    /**
     * UserRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     * @param string $rolePermissionTable
     * @param string $permissionTable
     */
    public function __construct(DatabaseInterface $database, string $table, string $rolePermissionTable, string $permissionTable)
    {
        $this->database = $database;
        $this->table = $table;
        $this->rolePermissionTable = $rolePermissionTable;
        $this->permissionTable = $permissionTable;
    }

    /**
     * Remove sensitive data from user array.
     * @param array $user
     * @return array
     */
    public function toPublic(array $user): array
    {
        unset($user['password'], $user['password_reset_token']);
        return $user;
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
            "WHERE username = :username;";
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
            "WHERE email = :email;";
        $params = ['email' => $email];

        $this->database->execute($query, $params);
        return $this->database->fetch() ?? null;
    }

    /**
     * Find all users.
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
            ($orderBy == null ? "" : "ORDER BY :order_by ").
            ($desc ? "DESC " : "").
            ($limit == null ? "" : "LIMIT :limit ").
            ($offset == null ? "" : "OFFSET :offset ").
            ";";
        \var_dump($query);

        $params = [
            'order_by' => \implode(", ", $orderBy),
            'limit' => $limit,
            'offset' => $offset
        ];

        $this->database->execute($query, $params);
        return $this->database->fetchAll() ?? [];
    }

    /**
     * Save user to database.
     * @param array $user
     * @return array User
     */
    public function save(array $user): array
    {

        $query =
            "INSERT INTO $this->table ".
            "(username, name, email, role_name, password, password_reset_token) ".
            "VALUES ".
            "(:username, :name, :email, :role_name, :password, :password_reset_token) ".
            "ON DUPLICATE KEY UPDATE ".
            "name = :name1, ".
            "email = :email1, ".
            "role_name = :role_name1, ".
            "password = :password1, ".
            "password_reset_token = :password_reset_token1;";
        $params = [
            'username' => $user['username'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_name' => $user['role_name'],
            'password' => $user['password'],
            'password_reset_token' => $user['password_reset_token'],
            'name1' => $user['name'],
            'email1' => $user['email'],
            'role_name1' => $user['role_name'],
            'password1' => $user['password'],
            'password_reset_token1' => $user['password_reset_token']
        ];

        $this->database->execute($query, $params);

        return $user;
    }

    /**
     * Delete the user from database.
     * @param string $username
     * @return bool
     */
    public function delete(string $username): bool
    {
        $query =
            "DELETE FROM $this->table ".
            "WHERE username = :username;";
        $params = ['username' => $username];

        $this->database->execute($query, $params);

        return $this->database->count() > 0;
    }

    /**
     * Hash the user's password.
     * @param string $password
     * @return string Hash.
     */
    public function hashPassword(string $password): string
    {
        return \password_hash($password, \PASSWORD_DEFAULT);
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

    /**
     * Find all permissions that has the given user.
     * @param string $username
     * @return array
     */
    public function findPermissionsNames(string $username): array
    {
        $query =
            "SELECT p.name ".
            "FROM $this->table AS u ".
            "JOIN $this->rolePermissionTable AS rp ".
            "ON u.role_name = rp.role_name ".
            "JOIN $this->permissionTable AS p ".
            "ON rp.permission_name = p.name ".
            "WHERE u.username = :username;";

        $params = [
            'username' => $username
        ];

        $this->database->execute($query, $params);
        return array_column($this->database->fetchAll(), "name") ?? [];
    }

    /**
     * Check whether the user has the permission.
     * @param string $username
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $username, string $permission): bool
    {
        return \in_array($permission, $this->findPermissionsNames($username));
    }
}