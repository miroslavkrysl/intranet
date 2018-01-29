<?php


namespace Intranet\Contracts\Repositories;


interface RoleRepositoryInterface
{
    /**
     * Find all roles.
     * @return array
     */
    public function findAll(): array;
}