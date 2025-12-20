<?php

namespace App\Services;

use App\Contracts\Repositories\TeamMemberRepositoryInterface;
use App\Contracts\Services\TeamMemberServiceInterface;
use App\Models\TeamMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TeamMemberService implements TeamMemberServiceInterface
{
    public function __construct(
        protected TeamMemberRepositoryInterface $repository
    ) {
    }

    /**
     * Get all team members.
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
     * Get paginated team members.
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
     * Find a team member by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return TeamMember|null
     */
    public function find($id, array $relations = []): ?TeamMember
    {
        return $this->findById($id, $relations);
    }

    /**
     * Find a team member by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return TeamMember|null
     */
    public function findById($id, array $relations = []): ?TeamMember
    {
        return $this->repository->findById($id, $relations);
    }

    /**
     * Find a team member by ID or fail.
     *
     * @param int|string $id
     * @param array $relations
     * @return TeamMember
     */
    public function findOrFail($id, array $relations = []): TeamMember
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Create a new team member.
     *
     * @param array $data
     * @return TeamMember
     */
    public function create(array $data): TeamMember
    {
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        return $this->repository->create($data);
    }

    /**
     * Update a team member.
     *
     * @param int|string $id
     * @param array $data
     * @return TeamMember
     */
    public function update($id, array $data): TeamMember
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a team member.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Filter team members by criteria.
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
     * Update team member status.
     *
     * @param int|string $id
     * @param string $status
     * @return TeamMember
     */
    public function updateStatus($id, string $status): TeamMember
    {
        if (!in_array($status, ['draft', 'published'])) {
            throw new \InvalidArgumentException("Invalid status: {$status}. Must be 'draft' or 'published'.");
        }

        return $this->repository->update($id, ['status' => $status]);
    }

    /**
     * Publish a team member.
     *
     * @param int|string $id
     * @return TeamMember
     */
    public function publish($id): TeamMember
    {
        return $this->updateStatus($id, 'published');
    }

    /**
     * Unpublish a team member.
     *
     * @param int|string $id
     * @return TeamMember
     */
    public function unpublish($id): TeamMember
    {
        return $this->updateStatus($id, 'draft');
    }
}
