<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SocialMediaLinkRepositoryInterface
{
    /**
     * Get all social media links.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated social media links.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a social media link by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return \App\Models\SocialMediaLink|null
     */
    public function find($id, array $relations = []);

    /**
     * Find a social media link by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return \App\Models\SocialMediaLink
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
     * @return \App\Models\SocialMediaLink|null
     */
    public function findOneBy(array $criteria, array $relations = []);

    /**
     * Create a new social media link.
     *
     * @param array $data
     * @return \App\Models\SocialMediaLink
     */
    public function create(array $data);

    /**
     * Update a social media link.
     *
     * @param int|string $id
     * @param array $data
     * @return \App\Models\SocialMediaLink
     */
    public function update($id, array $data);

    /**
     * Delete a social media link.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter social media links by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;
}
