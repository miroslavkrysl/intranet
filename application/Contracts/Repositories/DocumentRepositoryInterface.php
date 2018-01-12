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
     * Find document by username.
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username);

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

    /**
     * Find all documents.
     * @param int $limit
     * @param int $offset
     * @param string|null $sortBy
     * @param bool $des
     * @return array
     */
    public function findAll(int $limit = null, int $offset = null, string $sortBy = null, bool $des = false): array;
}