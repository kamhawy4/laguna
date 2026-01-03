<?php

namespace App\Contracts\Services;

interface BaseServiceInterface
{
    /**
     * Get all records.
     *
     * @param array $filters
     * @param array $relations
     * @return mixed
     */
    public function all(array $filters = [], array $relations = []);

    /**
     * Find a record by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return mixed
     */
    public function find($id, array $relations = []);

    /**
     * Find a record by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return mixed
     */
    public function findOrFail($id, array $relations = []);

}

