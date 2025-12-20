<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Find a record by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Model|null
     */
    public function find($id, array $relations = []): ?Model;

    /**
     * Find a record by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Model
     */
    public function findOrFail($id, array $relations = []): Model;

    /**
     * Find records by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return Collection
     */
    public function findBy(array $criteria, array $relations = []): Collection;

    /**
     * Find a single record by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return Model|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?Model;

    /**
     * Create a new record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record.
     *
     * @param int|string $id
     * @param array $data
     * @return Model
     */
    public function update($id, array $data): Model;

    /**
     * Delete a record.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Get paginated records.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []);
}

