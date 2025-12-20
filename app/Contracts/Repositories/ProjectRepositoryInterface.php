<?php

namespace App\Contracts\Repositories;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated projects.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function findById($id, array $relations = []): ?Project;

    /**
     * Find a project by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Project|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Project;

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project;

    /**
     * Update a project.
     *
     * @param int|string $id
     * @param array $data
     * @return Project
     */
    public function update($id, array $data): Project;

    /**
     * Delete a project.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter projects by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;
}

