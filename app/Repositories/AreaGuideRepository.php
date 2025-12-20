<?php

namespace App\Repositories;

use App\Contracts\Repositories\AreaGuideRepositoryInterface;
use App\Models\AreaGuide;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AreaGuideRepository implements AreaGuideRepositoryInterface
{
    /**
     * Get all area guides.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = AreaGuide::with($relations);

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * Find an area guide by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide|null
     */
    public function find($id, array $relations = []): ?AreaGuide
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find an area guide by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findById($id, array $relations = []): ?AreaGuide
    {
        return AreaGuide::with($relations)->find($id);
    }

    /**
     * Find an area guide by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide
     */
    public function findOrFail($id, array $relations = []): AreaGuide
    {
        return AreaGuide::with($relations)->findOrFail($id);
    }

    /**
     * Find an area guide by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?AreaGuide
    {
        $locale = $locale ?? app()->getLocale();

        return AreaGuide::with($relations)
            ->where("slug->{$locale}", $slug)
            ->first();
    }

    /**
     * Find records by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return Collection
     */
    public function findBy(array $criteria, array $relations = []): Collection
    {
        $query = AreaGuide::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * Find a single record by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?AreaGuide
    {
        $query = AreaGuide::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new area guide.
     *
     * @param array $data
     * @return AreaGuide
     */
    public function create(array $data): AreaGuide
    {
        return AreaGuide::create($data);
    }

    /**
     * Update an area guide.
     *
     * @param int|string $id
     * @param array $data
     * @return AreaGuide
     */
    public function update($id, array $data): AreaGuide
    {
        $areaGuide = $this->findOrFail($id);
        $areaGuide->update($data);

        return $areaGuide->fresh();
    }

    /**
     * Delete an area guide.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $areaGuide = $this->findOrFail($id);

        return $areaGuide->delete();
    }

    /**
     * Get paginated area guides.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = AreaGuide::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter area guides by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = AreaGuide::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Sync projects for an area guide.
     *
     * @param int|string $id
     * @param array $projectIds
     * @return AreaGuide
     */
    public function syncProjects($id, array $projectIds): AreaGuide
    {
        $areaGuide = $this->findOrFail($id);
        $areaGuide->projects()->sync($projectIds);

        return $areaGuide->fresh();
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters($query, array $filters): void
    {
        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Popular filter
        if (isset($filters['is_popular'])) {
            $query->where('is_popular', (bool) $filters['is_popular']);
        }

        // Search filter (searches in name and description)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("name->{$locale}", 'like', "%{$search}%")
                    ->orWhere("description->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}
