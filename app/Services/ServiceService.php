<?php

namespace App\Services;

use App\Contracts\Repositories\ServiceRepositoryInterface;
use App\Contracts\Services\ServiceServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ServiceService implements ServiceServiceInterface
{
    public function __construct(protected ServiceRepositoryInterface $repository)
    {
    }

    /**
     * Get all services.
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        return $this->repository->all($filters, $relations);
    }

    /**
     * Get paginated services.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters, $relations);
    }

    /**
     * Find a service by ID.
     */
    public function find($id, array $relations = [])
    {
        return $this->repository->find($id, $relations);
    }

    /**
     * Find a service by ID or fail.
     */
    public function findOrFail($id, array $relations = [])
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Find a service by slug.
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = [])
    {
        return $this->repository->findBySlug($slug, $locale, $relations);
    }

    /**
     * Create a new service.
     */
    public function create(array $data)
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
     * Update a service.
     */
    public function update($id, array $data)
    {
        // Generate slugs if title is being updated
        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title'], $id);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a service.
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Publish a service.
     */
    public function publish($id)
    {
        return $this->repository->update($id, ['status' => 'published']);
    }

    /**
     * Unpublish a service.
     */
    public function unpublish($id)
    {
        return $this->repository->update($id, ['status' => 'draft']);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured($id)
    {
        $service = $this->findOrFail($id);
        $service->is_featured = !$service->is_featured;
        $service->save();

        return $service;
    }

    /**
     * Generate slug for service.
     */
    public function generateSlug(string $title, ?int $excludeId = null): array
    {
        $slugs = [];
        $locales = config('app.available_locales', ['en', 'ar']);

        foreach ($locales as $locale) {
            $slug = Str::slug($title);
            $originalSlug = $slug;
            $counter = 1;

            while ($this->slugExists($slug, $locale, $excludeId)) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $slugs[$locale] = $slug;
        }

        return $slugs;
    }

    /**
     * Check if slug exists for locale.
     */
    private function slugExists(string $slug, string $locale, ?int $excludeId = null): bool
    {
        $query = \App\Models\Service::where("slug->{$locale}", $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
