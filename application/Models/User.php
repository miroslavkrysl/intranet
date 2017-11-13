<?php


namespace Intranet\Models;


use Intranet\Contracts\UserRepositoryInterface;


/**
 * User model.
 */
class User
{
    private $id;
    private $username;
    private $name;
    private $email;
    private $password;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    /**
     * Contains validation failures messages if the validation fails.
     * @var array
     */
    private $validationFailures;

    /**
     * Implementation of user repository.
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * User constructor. Create a new user using the data in given array.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
        $this->deletedAt = $data['deleted_at'] ?? null;

        $this->repository = app('repository.user');
    }

    /**
     * Validate the user and save validation errors eventually.
     * @return bool
     */
    public function validate(): bool
    {
        $ok = $this->repository->validate($this);
        if (!$ok) {
            $this->validationFailures = $this->repository->getValidationFailures();
        }
        return $ok;
    }

    /**
     * Save user and set validation errors eventually.
     * @return bool
     */
    public function save(): bool
    {
        $ok = $this->repository->save($this);
        if(!$ok) {
            $this->validationFailures = $this->repository->getValidationFailures();
        }
        return $ok;
    }

    /**
     * Delete the user.
     */
    public function delete()
    {
        $this->repository->delete($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function verifyPassword($password)
    {
        return \password_verify($password, $this->password);
    }

    /**
     * @param mixed $password
     */
    public function hashPassword($password)
    {
        $this->password = \password_hash($password, \PASSWORD_DEFAULT);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return array
     */
    public function getValidationFailures(): array
    {
        return $this->validationFailures;
    }
}