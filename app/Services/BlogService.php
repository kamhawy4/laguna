<?php

namespace App\Services;

use App\Contracts\Repositories\BlogRepositoryInterface;
use App\Contracts\Services\BlogServiceInterface;
use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BlogService implements BlogServiceInterface
{
    public function __construct(
        protected BlogRepositoryInterface $repository
    ) {
    }

    /**
     * Get all blogs.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        return $this->repository->all($filters, $relations);
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
        return $this->repository->paginate($perPage, $filters, $relations);
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
        return $this->repository->findById($id, $relations);
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
        return $this->repository->findOrFail($id, $relations);
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
        return $this->repository->findBySlug($slug, $locale, $relations);
    }

    /**
     * Create a new blog.
     *
     * @param array $data
     * @return Blog
     */
    public function create(array $data): Blog
    {
        // Generate slugs if title is provided
        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->repository->create($data);
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
        // Generate slugs if title is being updated
        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a blog.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
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
        return $this->repository->filter($filters, $relations);
    }

    /**
     * Generate slug from title for all locales.
     *
     * @param array|string $title
     * @param int|null $excludeId
     * @return array
     */
    public function generateSlug($title, ?int $excludeId = null): array
    {
        $slugs = [];
        $locales = config('app.available_locales', ['en', 'ar']);

        // If title is a string, use it for all locales
        if (is_string($title)) {
            $title = array_fill_keys($locales, $title);
        }

        foreach ($locales as $locale) {
            $titleValue = $title[$locale] ?? $title['en'] ?? '';
            
            if (empty($titleValue)) {
                continue;
            }

            $baseSlug = Str::slug($titleValue);
            $slug = $baseSlug;
            $counter = 1;

            // Ensure slug uniqueness
            while ($this->isSlugExists($slug, $locale, $excludeId)) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $slugs[$locale] = $slug;
        }

        return $slugs;
    }

    /**
     * Check if slug exists for a given locale.
     *
     * @param string $slug
     * @param string $locale
     * @param int|null $excludeId
     * @return bool
     */
    protected function isSlugExists(string $slug, string $locale, ?int $excludeId = null): bool
    {
        $query = Blog::where("slug->{$locale}", $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Update blog status.
     *
     * @param int|string $id
     * @param string $status
     * @return Blog
     */
    public function updateStatus($id, string $status): Blog
    {
        if (!in_array($status, ['draft', 'published'])) {
            throw new \InvalidArgumentException("Invalid status: {$status}. Must be 'draft' or 'published'.");
        }

        $data = ['status' => $status];

        // Set published_at when publishing
        if ($status === 'published') {
            $data['published_at'] = now();
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Publish a blog.
     *
     * @param int|string $id
     * @return Blog
     */
    public function publish($id): Blog
    {
        return $this->updateStatus($id, 'published');
    }

    /**
     * Unpublish a blog (set to draft).
     *
     * @param int|string $id
     * @return Blog
     */
    public function unpublish($id): Blog
    {
        return $this->updateStatus($id, 'draft');
    }

    /**
     * Toggle featured status.
     *
     * @param int|string $id
     * @return Blog
     */
    public function toggleFeatured($id): Blog
    {
        $blog = $this->findOrFail($id);
        $newStatus = !$blog->is_featured;

        return $this->repository->update($id, ['is_featured' => $newStatus]);
    }
}
