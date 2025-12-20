<?php

namespace App\Repositories;

use App\Contracts\Repositories\SocialMediaLinkRepositoryInterface;
use App\Models\SocialMediaLink;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SocialMediaLinkRepository implements SocialMediaLinkRepositoryInterface
{
    /**
     * Get all social media links.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = SocialMediaLink::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')->get();
    }

    /**
     * Find a social media link by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return SocialMediaLink|null
     */
    public function find($id, array $relations = []): ?SocialMediaLink
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a social media link by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return SocialMediaLink|null
     */
    public function findById($id, array $relations = []): ?SocialMediaLink
    {
        return SocialMediaLink::with($relations)->find($id);
    }

    /**
     * Find a social media link by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return SocialMediaLink
     */
    public function findOrFail($id, array $relations = []): SocialMediaLink
    {
        return SocialMediaLink::with($relations)->findOrFail($id);
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
        $query = SocialMediaLink::with($relations);

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
     * @return SocialMediaLink|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?SocialMediaLink
    {
        $query = SocialMediaLink::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new social media link.
     *
     * @param array $data
     * @return SocialMediaLink
     */
    public function create(array $data): SocialMediaLink
    {
        return SocialMediaLink::create($data);
    }

    /**
     * Update a social media link.
     *
     * @param int|string $id
     * @param array $data
     * @return SocialMediaLink
     */
    public function update($id, array $data): SocialMediaLink
    {
        $link = $this->findOrFail($id);
        $link->update($data);

        return $link->fresh();
    }

    /**
     * Delete a social media link.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $link = $this->findOrFail($id);

        return $link->delete();
    }

    /**
     * Get paginated social media links.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = SocialMediaLink::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter social media links by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = SocialMediaLink::with($relations);

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
        // Active filter
        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        // Platform filter
        if (isset($filters['platform'])) {
            $query->where('platform', $filters['platform']);
        }

        // Search filter (searches in label and platform)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where('platform', 'like', "%{$search}%")
                    ->orWhere("label->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}
