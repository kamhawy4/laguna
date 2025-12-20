<?php

namespace App\Services;

use App\Contracts\Repositories\TestimonialRepositoryInterface;
use App\Contracts\Services\TestimonialServiceInterface;
use App\Models\Testimonial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TestimonialService implements TestimonialServiceInterface
{
    public function __construct(protected TestimonialRepositoryInterface $repository)
    {
    }

    /**
     * Get all testimonials.
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        return $this->repository->all($filters, $relations);
    }

    /**
     * Get paginated testimonials.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters, $relations);
    }

    /**
     * Find a testimonial by ID.
     */
    public function find($id, array $relations = []): ?Testimonial
    {
        return $this->repository->find($id, $relations);
    }

    /**
     * Find a testimonial by ID or fail.
     */
    public function findOrFail($id, array $relations = []): Testimonial
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Find a testimonial by ID (alias for compatibility).
     */
    public function findBySlug(string $id, ?string $locale = null, array $relations = []): ?Testimonial
    {
        return $this->repository->findBySlug($id, $locale, $relations);
    }

    /**
     * Create a new testimonial.
     */
    public function create(array $data): Testimonial
    {
        $data['status'] = $data['status'] ?? 'draft';

        return $this->repository->create($data);
    }

    /**
     * Update a testimonial.
     */
    public function update($id, array $data): Testimonial
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a testimonial.
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Publish a testimonial.
     */
    public function publish($id): Testimonial
    {
        return $this->repository->update($id, ['status' => 'published']);
    }

    /**
     * Unpublish a testimonial.
     */
    public function unpublish($id): Testimonial
    {
        return $this->repository->update($id, ['status' => 'draft']);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured($id): Testimonial
    {
        $testimonial = $this->findOrFail($id);
        $testimonial->is_featured = !$testimonial->is_featured;
        $testimonial->save();

        return $testimonial;
    }
}
