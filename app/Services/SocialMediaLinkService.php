<?php

namespace App\Services;

use App\Contracts\Repositories\SocialMediaLinkRepositoryInterface;
use App\Contracts\Services\SocialMediaLinkServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SocialMediaLinkService implements SocialMediaLinkServiceInterface
{
    public function __construct(protected SocialMediaLinkRepositoryInterface $repository)
    {
    }

    /**
     * Get all social media links.
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        return $this->repository->all($filters, $relations);
    }

    /**
     * Get paginated social media links.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters, $relations);
    }

    /**
     * Find a social media link by ID.
     */
    public function find($id, array $relations = [])
    {
        return $this->repository->find($id, $relations);
    }

    /**
     * Find a social media link by ID or fail.
     */
    public function findOrFail($id, array $relations = [])
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Create a new social media link.
     */
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update a social media link.
     */
    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a social media link.
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive($id)
    {
        $link = $this->findOrFail($id);
        $link->is_active = !$link->is_active;
        $link->save();

        return $link;
    }

    /**
     * Reorder social media links.
     */
    public function reorder(array $order): void
    {
        foreach ($order as $item) {
            $this->repository->update($item['id'], ['order' => $item['order']]);
        }
    }
}
