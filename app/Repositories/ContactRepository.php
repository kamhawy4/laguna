<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * Get all contacts.
     */
    public function all(array $filters = [], array $relations = []): Collection
    {
        $query = Contact::with($relations);

        $this->applyFilters($query, $filters);

        return $query->latest()->get();
    }

    /**
     * Get paginated contacts.
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Contact::with($relations);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a contact by ID.
     */
    public function find($id, array $relations = []): ?Contact
    {
        return Contact::with($relations)->find($id);
    }

    /**
     * Find a contact by ID or fail.
     */
    public function findOrFail($id, array $relations = []): Contact
    {
        return Contact::with($relations)->findOrFail($id);
    }

    /**
     * Create a new contact.
     */
    public function create(array $data): Contact
    {
        // Capture IP address if not provided
        if (!isset($data['ip_address'])) {
            $data['ip_address'] = request()->ip();
        }

        return Contact::create($data);
    }

    /**
     * Update a contact.
     */
    public function update($id, array $data): Contact
    {
        $contact = $this->findOrFail($id);
        $contact->update($data);

        return $contact->fresh();
    }

    /**
     * Delete a contact.
     */
    public function delete($id): bool
    {
        $contact = $this->findOrFail($id);

        return $contact->delete();
    }

    /**
     * Filter contacts by criteria.
     */
    public function filter(array $filters = [], array $relations = []): Collection
    {
        $query = Contact::with($relations);

        $this->applyFilters($query, $filters);

        return $query->latest()->get();
    }

    /**
     * Apply filters to the query.
     */
    protected function applyFilters($query, array $filters): void
    {
        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Search filter (searches in name, email, subject, message)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Email filter
        if (isset($filters['email'])) {
            $query->where('email', $filters['email']);
        }
    }
}
