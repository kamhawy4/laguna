<?php

namespace App\Contracts\Services;

use App\Models\Setting;

interface SettingServiceInterface
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
     * Update settings.
     */
    public function update($id, array $data): Setting;

    /**
     * Toggle settings status.
     */
    public function toggleStatus($id): Setting;
}
