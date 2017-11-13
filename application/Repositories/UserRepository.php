<?php


namespace Intranet\Repositories;


use Core\Contracts\Database\DatabaseInterface;
use Intranet\Contracts\UserRepositoryInterface;
use Intranet\Models\User;


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
     * Contains validation failures messages if the validation failed.
     * @var array
     */
    private $validationFailures;

    /**
     * UserRepository constructor.
     * @param DatabaseInterface $database
     * @param string $table
     */
    public function __construct(DatabaseInterface $database, string $table)
    {
        $this->database = $database;
        $this->table = $table;

        $this->validationFailures = [];
    }

    /**
     * Find user by id.
     * @param int $id
     * @return User|null
     */
    public function findById(int $id)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $id];

        $data = $this->database->execute($query, $params)->fetch();
        return new User($data);
    }

    /**
     * Find user by username.
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE username = :username;";
        $params = ['username' => $username];

        $data = $this->database->execute($query, $params)->fetch();
        return new User($data);
    }

    /**
     * Find user by email.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email)
    {
        $query =
            "SELECT * ".
            "FROM $this->table ".
            "WHERE email = :email;";
        $params = ['email' => $email];

        $data = $this->database->execute($query, $params)->fetch();
        return new User($data);
    }

    /**
     * Validate the user and set validation failures eventually.
     * @param User $user
     * @return bool
     */
    public function validate(User $user): bool
    {
        // TODO: validation logic
    }

    /**
     * Get validation failures messages.
     * @return array|null
     */
    public function getValidationFailures(): array
    {
        return $this->validationFailures;
    }

    /**
     * Save user to database.
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool
    {
        if(!$this->validate($user)) {
            return false;
        }

        if ($this->exists($user)) {
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
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'deleted_at' => $user->getDeletedAt(),
                'id' => $user->getId()
            ];
        }
        else {
            $query =
                "INSERT INTO $this->table ".
                "(username, password, name, email, deleted_at) ".
                "VALUES ".
                "(:username, :password, :name, :email, :deleted_at);";
            $params = [
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'deleted_at' => $user->getDeletedAt()
            ];
        }

        $this->database->execute($query, $params);

        return true;
    }

    /**
     * Delete the user from database.
     * @param User $user
     * @return int
     */
    public function delete(User $user): bool
    {
        if (!$this->exists($user)) {
            return false;
        }

        $query =
            "DELETE FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $user->getId()];

        $this->database->execute($query, $params);

        return true;
    }

    /**
     * Determine if the user exists.
     * @param User $user
     * @return bool
     */
    public function exists(User $user): bool
    {
        if (!$user->getId()) {
            return false;
        }

        $query =
            "SELECT id ".
            "FROM $this->table ".
            "WHERE id = :id;";
        $params = ['id' => $user->getId()];

        return $this->database->execute($query, $params)->count() > 0;
    }
}