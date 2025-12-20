<?php

namespace App\Contracts\Repositories;

use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TestimonialRepositoryInterface extends BaseRepositoryInterface
{
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
    public function findById($id, array $relations = []): ?Testimonial;

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
     * Filter testimonials by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;
}
