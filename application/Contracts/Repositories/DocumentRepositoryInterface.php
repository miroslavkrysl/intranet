<?php


namespace Intranet\Contracts\Repositories;


interface DocumentRepositoryInterface
{
    /**
     * Find document by id.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id);

    /**
     * Find documents by username.
     * @param string $username
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findByUsername(string $username, array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array ;

    /**
     * Find all documents.
     * @param array|null $orderBy
     * @param bool $desc
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findAll(array $orderBy = null, bool $desc = false, int $limit = null, int $offset = null): array;

    /**
     * Save document to database.
     * @param array $document
     * @return boolean
     */
    public function save(array $document): bool;

    /**
     * Delete the document from the database.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}