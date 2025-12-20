<?php

namespace App\Contracts\Services;

use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TestimonialServiceInterface
{
    /**
     * Get all testimonials.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated testimonials.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a testimonial by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Testimonial|null
     */
    public function find($id, array $relations = []): ?Testimonial;

    /**
     * Find a testimonial by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Testimonial
     */
    public function findOrFail($id, array $relations = []): Testimonial;

    /**
     * Create a new testimonial.
     *
     * @param array $data
     * @return Testimonial
     */
    public function create(array $data): Testimonial;

    /**
     * Update a testimonial.
     *
     * @param int|string $id
     * @param array $data
     * @return Testimonial
     */
    public function update($id, array $data): Testimonial;

    /**
     * Delete a testimonial.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Publish a testimonial.
     *
     * @param int|string $id
     * @return Testimonial
     */
    public function publish($id): Testimonial;

    /**
     * Unpublish a testimonial.
     *
     * @param int|string $id
     * @return Testimonial
     */
    public function unpublish($id): Testimonial;

    /**
     * Toggle featured status.
     *
     * @param int|string $id
     * @return Testimonial
     */
    public function toggleFeatured($id): Testimonial;
}
