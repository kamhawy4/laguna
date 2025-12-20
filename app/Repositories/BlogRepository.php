<?php

namespace App\Repositories;

use App\Contracts\Repositories\BlogRepositoryInterface;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository implements BlogRepositoryInterface
{
    /**
     * Get all blogs.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = Blog::with($relations);

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * Find a blog by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Blog|null
     */
    public function find($id, array $relations = []): ?Blog
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a blog by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Blog|null
     */
    public function findById($id, array $relations = []): ?Blog
    {
        return Blog::with($relations)->find($id);
    }

    /**
     * Find a blog by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Blog
     */
    public function findOrFail($id, array $relations = []): Blog
    {
        return Blog::with($relations)->findOrFail($id);
    }

    /**
     * Find a blog by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Blog|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Blog
    {
        $locale = $locale ?? app()->getLocale();

        return Blog::with($relations)
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
        $query = Blog::with($relations);

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
     * @return Blog|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?Blog
    {
        $query = Blog::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new blog.
     *
     * @param array $data
     * @return Blog
     */
    public function create(array $data): Blog
    {
        return Blog::create($data);
    }

    /**
     * Update a blog.
     *
     * @param int|string $id
     * @param array $data
     * @return Blog
     */
    public function update($id, array $data): Blog
    {
        $blog = $this->findOrFail($id);
        $blog->update($data);

        return $blog->fresh();
    }

    /**
     * Delete a blog.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $blog = $this->findOrFail($id);

        return $blog->delete();
    }

    /**
     * Get paginated blogs.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Blog::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Filter blogs by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = Blog::with($relations);

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

        // Published date filter
        if (isset($filters['published_from'])) {
            $query->whereDate('published_at', '>=', $filters['published_from']);
        }

        if (isset($filters['published_to'])) {
            $query->whereDate('published_at', '<=', $filters['published_to']);
        }

        // Search filter (searches in title and content)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("content->{$locale}", 'like', "%{$search}%")
                    ->orWhere("short_description->{$locale}", 'like', "%{$search}%");
            });
        }
    }
}
