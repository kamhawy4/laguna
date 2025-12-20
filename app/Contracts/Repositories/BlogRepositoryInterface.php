<?php

namespace App\Contracts\Repositories;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
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
}