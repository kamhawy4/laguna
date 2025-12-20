<?php

namespace App\Contracts\Services;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProjectServiceInterface extends BaseServiceInterface
{
    /**
     * Get all projects.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated projects.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function findById($id, array $relations = []): ?Project;

    /**
     * Find a project by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Project|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Project;

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project;

    /**
     * Update a project.
     *
     * @param int|string $id
     * @param array $data
     * @return Project
     */
    public function update($id, array $data): Project;

    /**
     * Delete a project.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter projects by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;

    /**
     * Generate slug from name for all locales.
     *
     * @param array|string $name
     * @param int|null $excludeId
     * @return array
     */
    public function generateSlug($name, ?int $excludeId = null): array;

    /**
     * Update project status.
     *
     * @param int|string $id
     * @param string $status
     * @return Project
     */
    public function updateStatus($id, string $status): Project;

    /**
     * Publish a project.
     *
     * @param int|string $id
     * @return Project
     */
    public function publish($id): Project;

    /**
     * Unpublish a project (set to draft).
     *
     * @param int|string $id
     * @return Project
     */
    public function unpublish($id): Project;

    /**
     * Toggle featured status.
     *
     * @param int|string $id
     * @return Project
     */
    public function toggleFeatured($id): Project;

    /**
     * Convert price from one currency to another.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float;

    /**
     * Sync prices across all currencies based on exchange rates.
     *
     * @param int|string $id
     * @param array $exchangeRates ['usd_to_aed' => 3.67, 'eur_to_aed' => 4.0]
     * @return Project
     */
    public function syncPrices($id, array $exchangeRates): Project;

    /**
     * Validate and format price data.
     *
     * @param array $data
     * @return array
     */
    public function formatPriceData(array $data): array;
}

