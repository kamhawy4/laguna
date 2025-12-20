<?php

namespace App\Repositories;

use App\Contracts\Repositories\TestimonialRepositoryInterface;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    /**
     * Get all testimonials.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = Testimonial::with($relations);

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * Find a testimonial by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Testimonial|null
     */
    public function find($id, array $relations = []): ?Testimonial
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a testimonial by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Testimonial|null
     */
    public function findById($id, array $relations = []): ?Testimonial
    {
        return Testimonial::with($relations)->find($id);
    }

    /**
     * Find a testimonial by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Testimonial
     */
    public function findOrFail($id, array $relations = []): Testimonial
    {
        return Testimonial::with($relations)->findOrFail($id);
    }

    /**
     * Find a testimonial by ID (alias).
     *
     * @param string $id
     * @param string|null $locale
     * @param array $relations
     * @return Testimonial|null
     */
    public function findBySlug(string $id, ?string $locale = null, array $relations = []): ?Testimonial
    {
        // For testimonials, we use ID-based lookup since they don't have slugs
        return $this->findById($id, $relations);
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
        $query = Testimonial::with($relations);

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
     * @return Testimonial|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?Testimonial
    {
        $query = Testimonial::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new testimonial.
     *
     * @param array $data
     * @return Testimonial
     */
    public function create(array $data): Testimonial
    {
        return Testimonial::create($data);
    }

    /**
     * Update a testimonial.
     *
     * @param int|string $id
     * @param array $data
     * @return Testimonial
     */
    public function update($id, array $data): Testimonial
    {
        $testimonial = $this->findOrFail($id);
        $testimonial->update($data);

        return $testimonial->fresh();
    }

    /**
     * Delete a testimonial.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $testimonial = $this->findOrFail($id);

        return $testimonial->delete();
    }

    /**
     * Get paginated testimonials.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Testimonial::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter testimonials by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = Testimonial::with($relations);

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

        // Rating filter
        if (isset($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        // Search filter (searches in client name and testimonial)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("client_name->{$locale}", 'like', "%{$search}%")
                    ->orWhere("testimonial->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}
