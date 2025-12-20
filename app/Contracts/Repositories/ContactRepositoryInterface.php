<?php

namespace App\Contracts\Repositories;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface
{
    /**
     * Get all contacts.
     */
    public function all(array $filters = [], array $relations = []): Collection;

    /**
     * Get paginated contacts.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find a contact by ID.
     */
    public function find($id, array $relations = []): ?Contact;

    /**
     * Find a contact by ID or fail.
     */
    public function findOrFail($id, array $relations = []): Contact;

    /**
     * Create a new contact.
     */
    public function create(array $data): Contact;

    /**
     * Update a contact.
     */
    public function update($id, array $data): Contact;

    /**
     * Delete a contact.
     */
    public function delete($id): bool;

    /**
     * Filter contacts by criteria.
     */
    public function filter(array $filters = [], array $relations = []): Collection;
}
