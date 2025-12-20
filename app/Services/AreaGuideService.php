<?php

namespace App\Services;

use App\Contracts\Repositories\AreaGuideRepositoryInterface;
use App\Contracts\Services\AreaGuideServiceInterface;
use App\Models\AreaGuide;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class AreaGuideService implements AreaGuideServiceInterface
{
    public function __construct(
        protected AreaGuideRepositoryInterface $repository
    ) {
    }

    /**
     * Get all area guides.
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
     * Get paginated area guides.
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
     * Find an area guide by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide|null
     */
    public function find($id, array $relations = []): ?AreaGuide
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find an area guide by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findById($id, array $relations = []): ?AreaGuide
    {
        return $this->repository->findById($id, $relations);
    }

    /**
     * Find an area guide by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return AreaGuide
     */
    public function findOrFail($id, array $relations = []): AreaGuide
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Find an area guide by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return AreaGuide|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?AreaGuide
    {
        return $this->repository->findBySlug($slug, $locale, $relations);
    }

    /**
     * Create a new area guide.
     *
     * @param array $data
     * @return AreaGuide
     */
    public function create(array $data): AreaGuide
    {
        // Generate slugs if name is provided
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->repository->create($data);
    }

    /**
     * Update an area guide.
     *
     * @param int|string $id
     * @param array $data
     * @return AreaGuide
     */
    public function update($id, array $data): AreaGuide
    {
        // Generate slugs if name is being updated
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete an area guide.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Filter area guides by criteria.
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
     * Generate slug from name for all locales.
     *
     * @param array|string $name
     * @param int|null $excludeId
     * @return array
     */
    public function generateSlug($name, ?int $excludeId = null): array
    {
        $slugs = [];
        $locales = config('app.available_locales', ['en', 'ar']);

        // If name is a string, use it for all locales
        if (is_string($name)) {
            $name = array_fill_keys($locales, $name);
        }

        foreach ($locales as $locale) {
            $nameValue = $name[$locale] ?? $name['en'] ?? '';
            
            if (empty($nameValue)) {
                continue;
            }

            $baseSlug = Str::slug($nameValue);
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
        $query = AreaGuide::where("slug->{$locale}", $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Update area guide status.
     *
     * @param int|string $id
     * @param string $status
     * @return AreaGuide
     */
    public function updateStatus($id, string $status): AreaGuide
    {
        if (!in_array($status, ['draft', 'published'])) {
            throw new \InvalidArgumentException("Invalid status: {$status}. Must be 'draft' or 'published'.");
        }

        return $this->repository->update($id, ['status' => $status]);
    }

    /**
     * Publish an area guide.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function publish($id): AreaGuide
    {
        return $this->updateStatus($id, 'published');
    }

    /**
     * Unpublish an area guide.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function unpublish($id): AreaGuide
    {
        return $this->updateStatus($id, 'draft');
    }

    /**
     * Toggle popular status.
     *
     * @param int|string $id
     * @return AreaGuide
     */
    public function togglePopular($id): AreaGuide
    {
        $areaGuide = $this->findOrFail($id);
        $newStatus = !$areaGuide->is_popular;

        return $this->repository->update($id, ['is_popular' => $newStatus]);
    }

    /**
     * Sync projects for an area guide.
     *
     * @param int|string $id
     * @param array $projectIds
     * @return AreaGuide
     */
    public function syncProjects($id, array $projectIds): AreaGuide
    {
        return $this->repository->syncProjects($id, $projectIds);
    }
}
