<?php

namespace App\Contracts\Repositories;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    /**
     * Get the first (and usually only) setting record.
     */
    public function first(): ?Setting;

    /**
     * Get a setting by ID.
     */
    public function find($id): ?Setting;

    /**
     * Find a setting by ID or fail.
     */
    public function findOrFail($id): Setting;

    /**
     * Create a new setting.
     */
    public function create(array $data): Setting;

    /**
     * Update a setting.
     */
    public function update($id, array $data): Setting;

    /**
     * Delete a setting.
     */
    public function delete($id): bool;
}
