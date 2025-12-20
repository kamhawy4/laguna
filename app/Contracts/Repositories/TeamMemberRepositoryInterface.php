<?php

namespace App\Contracts\Repositories;

use App\Models\TeamMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TeamMemberRepositoryInterface extends BaseRepositoryInterface
{
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
}
