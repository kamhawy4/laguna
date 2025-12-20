<?php

namespace App\Repositories;

use App\Contracts\Repositories\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    /**
     * Get all team members.
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = TeamMember::with($relations);

        $this->applyFilters($query, $filters);

        return $query->get();
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
        return TeamMember::with($relations)->find($id);
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
        return TeamMember::with($relations)->findOrFail($id);
    }

    /**
     * Find records by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return Collection
     */
    public function findBy(array $criteria, array $relations = []): Collection
    {
        $query = TeamMember::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    /**
     * Find a single record by criteria.
     *
     * @param array $criteria
     * @param array $relations
     * @return TeamMember|null
     */
    public function findOneBy(array $criteria, array $relations = []): ?TeamMember
    {
        $query = TeamMember::with($relations);

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Create a new team member.
     *
     * @param array $data
     * @return TeamMember
     */
    public function create(array $data): TeamMember
    {
        return TeamMember::create($data);
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
        $member = $this->findOrFail($id);
        $member->update($data);

        return $member->fresh();
    }

    /**
     * Delete a team member.
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $member = $this->findOrFail($id);

        return $member->delete();
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
        $query = TeamMember::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
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
        $query = TeamMember::with($relations);

        $this->applyFilters($query, $filters);

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Apply filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters($query, array $filters): void
    {
        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Search filter (searches in name and job title)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("name->{$locale}", 'like', "%{$search}%")
                    ->orWhere("job_title->{$locale}", 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
    }
}
