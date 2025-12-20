<?php

namespace App\Repositories;

use App\Contracts\Repositories\ServiceRepositoryInterface;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * Get all services.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = Service::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')->get();
    }

    /**
     * Find a service by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Service|null
     */
    public function find($id, array $relations = []): ?Service
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a service by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Service|null
     */
    public function findById($id, array $relations = []): ?Service
    {
        return Service::with($relations)->find($id);
    }

    /**
     * Find a service by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Service
     */
    public function findOrFail($id, array $relations = []): Service
    {
        return Service::with($relations)->findOrFail($id);
    }

    /**
     * Find a service by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Service|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Service
    {
        $locale = $locale ?? app()->getLocale();

        return Service::with($relations)
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
        $query = Service::with($relations);

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
     * @return Service|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?Service
    {
        $query = Service::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new service.
     *
     * @param array $data
     * @return Service
     */
    public function create(array $data): Service
    {
        return Service::create($data);
    }

    /**
     * Update a service.
     *
     * @param int|string $id
     * @param array $data
     * @return Service
     */
    public function update($id, array $data): Service
    {
        $service = $this->findOrFail($id);
        $service->update($data);

        return $service->fresh();
    }

    /**
     * Delete a service.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $service = $this->findOrFail($id);

        return $service->delete();
    }

    /**
     * Get paginated services.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Service::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter services by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = Service::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
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

        // Search filter (searches in title and description)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("short_description->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}
