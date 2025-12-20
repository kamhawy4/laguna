<?php

namespace App\Contracts\Services;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BlogServiceInterface extends BaseServiceInterface
{
    /**
     * Get all blogs.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated blogs.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a blog by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Blog|null
     */
    public function findById($id, array $relations = []): ?Blog;

    /**
     * Find a blog by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Blog|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Blog;

    /**
     * Create a new blog.
     *
     * @param array $data
     * @return Blog
     */
    public function create(array $data): Blog;

    /**
     * Update a blog.
     *
     * @param int|string $id
     * @param array $data
     * @return Blog
     */
    public function update($id, array $data): Blog;

    /**
     * Delete a blog.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter blogs by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;

    /**
     * Generate slug from title for all locales.
     *
     * @param array|string $title
     * @param int|null $excludeId
     * @return array
     */
    public function generateSlug($title, ?int $excludeId = null): array;

    /**
     * Update blog status.
     *
     * @param int|string $id
     * @param string $status
     * @return Blog
     */
    public function updateStatus($id, string $status): Blog;

    /**
     * Publish a blog.
     *
     * @param int|string $id
     * @return Blog
     */
    public function publish($id): Blog;

    /**
     * Unpublish a blog (set to draft).
     *
     * @param int|string $id
     * @return Blog
     */
    public function unpublish($id): Blog;

    /**
     * Toggle featured status.
     *
     * @param int|string $id
     * @return Blog
     */
    public function toggleFeatured($id): Blog;
}
