<?php

namespace App\Contracts\Services;

use App\Models\AreaGuide;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AreaGuideServiceInterface extends BaseServiceInterface
{
    /**
     * Get all area guides.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated area guides.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find an area guide by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findById($id, array $relations = []): ?AreaGuide;

    /**
     * Find an area guide by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?AreaGuide;

    /**
     * Create a new area guide.
     *
     * @param array $data
     * @return AreaGuide
     */
    public function create(array $data): AreaGuide;

    /**
     * Update an area guide.
     *
     * @param int|string $id
     * @param array $data
     * @return AreaGuide
     */
    public function update($id, array $data): AreaGuide;

    /**
     * Delete an area guide.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter area guides by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;

    /**
     * Generate slug from name for all locales.
     *
     * @param array|string $name
     * @param int|null $excludeId
     * @return array
     */
    public function generateSlug($name, ?int $excludeId = null): array;

    /**
     * Update area guide status.
     *
     * @param int|string $id
     * @param string $status
     * @return AreaGuide
     */
    public function updateStatus($id, string $status): AreaGuide;

    /**
     * Publish an area guide.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function publish($id): AreaGuide;

    /**
     * Unpublish an area guide.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function unpublish($id): AreaGuide;

    /**
     * Toggle popular status.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function togglePopular($id): AreaGuide;

    /**
     * Sync projects for an area guide.
     *
     * @param int|string $id
     * @param array $projectIds
     * @return AreaGuide
     */
    public function syncProjects($id, array $projectIds): AreaGuide;
}
