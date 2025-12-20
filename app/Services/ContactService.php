<?php

namespace App\Services;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Contracts\Services\ContactServiceInterface;
use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactService implements ContactServiceInterface
{
    public function __construct(
        protected ContactRepositoryInterface $repository
    ) {
    }

    /**
     * Get all contacts.
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        return $this->repository->all($filters, $relations);
    }

    /**
     * Get paginated contacts.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters, $relations);
    }

    /**
     * Find a contact by ID.
     */
    public function find($id, array $relations = []): ?Contact
    {
        return $this->repository->find($id, $relations);
    }

    /**
     * Find a contact by ID or fail.
     */
    public function findOrFail($id, array $relations = []): Contact
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Create a new contact message.
     */
    public function create(array $data): Contact
    {
        // Set default status to 'new'
        $data['status'] = 'new';

        return $this->repository->create($data);
    }

    /**
     * Update a contact.
     */
    public function update($id, array $data): Contact
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a contact.
     */
    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Mark a contact as read.
     */
    public function markAsRead($id): Contact
    {
        return $this->update($id, ['status' => 'read']);
    }

    /**
     * Mark a contact as closed.
     */
    public function markAsClosed($id): Contact
    {
        return $this->update($id, ['status' => 'closed']);
    }
}
