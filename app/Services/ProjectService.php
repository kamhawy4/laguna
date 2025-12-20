<?php

namespace App\Services;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Contracts\Services\ProjectServiceInterface;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProjectService implements ProjectServiceInterface
{
    /**
     * Supported currencies.
     */
    protected const CURRENCIES = ['AED', 'USD', 'EUR'];

    /**
     * Default exchange rates (AED as base).
     */
    protected const DEFAULT_EXCHANGE_RATES = [
        'usd_to_aed' => 3.67,
        'eur_to_aed' => 4.0,
    ];

    public function __construct(
        protected ProjectRepositoryInterface $repository
    ) {
    }

    /**
     * Get all projects.
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
     * Get paginated projects.
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
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function find($id, array $relations = []): ?Project
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project|null
     */
    public function findById($id, array $relations = []): ?Project
    {
        return $this->repository->findById($id, $relations);
    }

    /**
     * Find a project by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return Project
     */
    public function findOrFail($id, array $relations = []): Project
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Find a project by slug.
     *
     * @param string $slug
     * @param string|null $locale
     * @param array $relations
     * @return Project|null
     */
    public function findBySlug(string $slug, ?string $locale = null, array $relations = []): ?Project
    {
        return $this->repository->findBySlug($slug, $locale, $relations);
    }

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project
    {
        // Generate slugs if name is provided
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        // Format price data
        $data = $this->formatPriceData($data);

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->repository->create($data);
    }

    /**
     * Update a project.
     *
     * @param int|string $id
     * @param array $data
     * @return Project
     */
    public function update($id, array $data): Project
    {
        // Generate slugs if name is being updated
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name'], $id);
        }

        // Format price data
        $data = $this->formatPriceData($data);

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a project.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Filter projects by criteria.
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
        $query = Project::where("slug->{$locale}", $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Update project status.
     *
     * @param int|string $id
     * @param string $status
     * @return Project
     */
    public function updateStatus($id, string $status): Project
    {
        if (!in_array($status, ['draft', 'published'])) {
            throw new \InvalidArgumentException("Invalid status: {$status}. Must be 'draft' or 'published'.");
        }

        return $this->repository->update($id, ['status' => $status]);
    }

    /**
     * Publish a project.
     *
     * @param int|string $id
     * @return Project
     */
    public function publish($id): Project
    {
        return $this->updateStatus($id, 'published');
    }

    /**
     * Unpublish a project (set to draft).
     *
     * @param int|string $id
     * @return Project
     */
    public function unpublish($id): Project
    {
        return $this->updateStatus($id, 'draft');
    }

    /**
     * Toggle featured status.
     *
     * @param int|string $id
     * @return Project
     */
    public function toggleFeatured($id): Project
    {
        $project = $this->findOrFail($id);
        $newStatus = !$project->is_featured;

        return $this->repository->update($id, ['is_featured' => $newStatus]);
    }

    /**
     * Convert price from one currency to another.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convertCurrency(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        if (!in_array($fromCurrency, self::CURRENCIES) || !in_array($toCurrency, self::CURRENCIES)) {
            throw new \InvalidArgumentException("Unsupported currency. Supported currencies: " . implode(', ', self::CURRENCIES));
        }

        // Convert to AED first (base currency)
        $amountInAed = $this->convertToAed($amount, $fromCurrency);

        // Convert from AED to target currency
        return $this->convertFromAed($amountInAed, $toCurrency);
    }

    /**
     * Convert amount to AED.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     */
    protected function convertToAed(float $amount, string $currency): float
    {
        return match (strtoupper($currency)) {
            'AED' => $amount,
            'USD' => $amount * self::DEFAULT_EXCHANGE_RATES['usd_to_aed'],
            'EUR' => $amount * self::DEFAULT_EXCHANGE_RATES['eur_to_aed'],
            default => throw new \InvalidArgumentException("Unsupported currency: {$currency}"),
        };
    }

    /**
     * Convert amount from AED to target currency.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     */
    protected function convertFromAed(float $amount, string $currency): float
    {
        return match (strtoupper($currency)) {
            'AED' => $amount,
            'USD' => $amount / self::DEFAULT_EXCHANGE_RATES['usd_to_aed'],
            'EUR' => $amount / self::DEFAULT_EXCHANGE_RATES['eur_to_aed'],
            default => throw new \InvalidArgumentException("Unsupported currency: {$currency}"),
        };
    }

    /**
     * Sync prices across all currencies based on exchange rates.
     *
     * @param int|string $id
     * @param array $exchangeRates ['usd_to_aed' => 3.67, 'eur_to_aed' => 4.0]
     * @return Project
     */
    public function syncPrices($id, array $exchangeRates): Project
    {
        $project = $this->findOrFail($id);
        $rates = array_merge(self::DEFAULT_EXCHANGE_RATES, $exchangeRates);

        $priceData = [];

        // Determine base price (first non-null price)
        $basePrice = $project->price_aed ?? $project->price_usd ?? $project->price_eur;
        $baseCurrency = $project->price_aed ? 'AED' : ($project->price_usd ? 'USD' : 'EUR');

        if ($basePrice === null) {
            throw new \InvalidArgumentException('No base price found to sync from.');
        }

        // Convert base price to AED
        $priceInAed = $this->convertToAed($basePrice, $baseCurrency);

        // Calculate all prices from AED
        $priceData['price_aed'] = round($priceInAed, 2);
        $priceData['price_usd'] = round($priceInAed / $rates['usd_to_aed'], 2);
        $priceData['price_eur'] = round($priceInAed / $rates['eur_to_aed'], 2);

        return $this->repository->update($id, $priceData);
    }

    /**
     * Validate and format price data.
     *
     * @param array $data
     * @return array
     */
    public function formatPriceData(array $data): array
    {
        // Ensure price values are properly formatted
        foreach (['price_aed', 'price_usd', 'price_eur'] as $priceField) {
            if (isset($data[$priceField])) {
                $data[$priceField] = is_numeric($data[$priceField]) 
                    ? round((float) $data[$priceField], 2) 
                    : null;
            }
        }

        // If only one price is provided, sync others based on default rates
        $providedPrices = array_filter([
            'AED' => $data['price_aed'] ?? null,
            'USD' => $data['price_usd'] ?? null,
            'EUR' => $data['price_eur'] ?? null,
        ]);

        if (count($providedPrices) === 1 && !isset($data['_skip_auto_sync'])) {
            $currency = array_key_first($providedPrices);
            $amount = $providedPrices[$currency];
            $priceInAed = $this->convertToAed($amount, $currency);

            $data['price_aed'] = round($priceInAed, 2);
            $data['price_usd'] = round($priceInAed / self::DEFAULT_EXCHANGE_RATES['usd_to_aed'], 2);
            $data['price_eur'] = round($priceInAed / self::DEFAULT_EXCHANGE_RATES['eur_to_aed'], 2);
        }

        return $data;
    }
}

