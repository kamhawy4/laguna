<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    /**
     * Get all services.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated services.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a service by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return \App\Models\Service|null
     */
    public function find($id, array $relations = []);

    /**
     * Find a service by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return \App\Models\Service
     */
    public function findOrFail($id, array $relations = []);

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
     * @return \App\Models\Service|null
     */
    public function findOneBy(array $criteria, array $relations = []);

    /**
     * Create a new service.
     *
     * @param array $data
     * @return \App\Models\Service
     */
    public function create(array $data);

    /**
     * Update a service.
     *
     * @param int|string $id
     * @param array $data
     * @return \App\Models\Service
     */
    public function update($id, array $data);

    /**
     * Delete a service.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter services by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;
}
