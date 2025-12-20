<?php

namespace App\Contracts\Services;

use App\Models\TeamMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TeamMemberServiceInterface extends BaseServiceInterface
{
    /**
     * Get all team members.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated team members.
     *
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a team member by ID.
     *
     * @param int|string $id
     * @param array $relations
     * @return TeamMember|null
     */
    public function findById($id, array $relations = []): ?TeamMember;

    /**
     * Create a new team member.
     *
     * @param array $data
     * @return TeamMember
     */
    public function create(array $data): TeamMember;

    /**
     * Update a team member.
     *
     * @param int|string $id
     * @param array $data
     * @return TeamMember
     */
    public function update($id, array $data): TeamMember;

    /**
     * Delete a team member.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Filter team members by criteria.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function filter(array $filters = [], array $relations = []): Collection;

    /**
     * Update team member status.
     *
     * @param int|string $id
     * @param string $status
     * @return TeamMember
     */
    public function updateStatus($id, string $status): TeamMember;

    /**
     * Publish a team member.
     *
     * @param int|string $id
     * @return TeamMember
     */
    public function publish($id): TeamMember;

    /**
     * Unpublish a team member.
     *
     * @param int|string $id
     * @return TeamMember
     */
    public function unpublish($id): TeamMember;
}
