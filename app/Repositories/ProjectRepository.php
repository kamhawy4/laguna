<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    /**
     * Get all projects.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = Project::with($relations);

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function find($id, array $relations = []): ?Project
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function findById($id, array $relations = []): ?Project
    {
        return Project::with($relations)->find($id);
    }

    /**
     * Find a project by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project
     */
    public function findOrFail($id, array $relations = []): Project
    {
        return Project::with($relations)->findOrFail($id);
    }

    /**
     * Find a project by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Project|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Project
    {
        $locale = $locale ?? app()->getLocale();

        return Project::with($relations)
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
        $query = Project::with($relations);

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
     * @return Project|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?Project
    {
        $query = Project::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update a project.
     *
     * @param int|string $id
     * @param array $data
     * @return Project
     */
    public function update($id, array $data): Project
    {
        $project = $this->findOrFail($id);
        $project->update($data);

        return $project->fresh();
    }

    /**
     * Delete a project.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $project = $this->findOrFail($id);

        return $project->delete();
    }

    /**
     * Get paginated projects.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Project::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter projects by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = Project::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
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

        // Featured filter
        if (isset($filters['is_featured'])) {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        // Area filter
        if (isset($filters['area'])) {
            $query->where('area', $filters['area']);
        }

        // Property type filter
        if (isset($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Delivery date filter
        if (isset($filters['delivery_date_from'])) {
            $query->where('delivery_date', '>=', $filters['delivery_date_from']);
        }

        if (isset($filters['delivery_date_to'])) {
            $query->where('delivery_date', '<=', $filters['delivery_date_to']);
        }

        // Price range filter (AED)
        if (isset($filters['price_min'])) {
            $query->where('price_aed', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max'])) {
            $query->where('price_aed', '<=', $filters['price_max']);
        }

        // Developer filter
        $locale = app()->getLocale();
        if (isset($filters['developer_name'])) {
            $query->where("developer_name->{$locale}", 'like', '%' . $filters['developer_name'] . '%');
        }

        // Search filter (searches in name and description)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search, $locale) {
                $q->where("name->{$locale}", 'like', "%{$search}%")
                    ->orWhere("description->{$locale}", 'like', "%{$search}%")
                    ->orWhere("short_description->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}

