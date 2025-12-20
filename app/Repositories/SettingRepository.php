<?php

namespace App\Repositories;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    /**
     * Get the first (and usually only) setting record.
     */
    public function first(): ?Setting
    {
        return Setting::first();
    }

    /**
     * Get a setting by ID.
     */
    public function find($id): ?Setting
    {
        return Setting::find($id);
    }

    /**
     * Find a setting by ID or fail.
     */
    public function findOrFail($id): Setting
    {
        return Setting::findOrFail($id);
    }

    /**
     * Create a new setting.
     */
    public function create(array $data): Setting
    {
        return Setting::create($data);
    }

    /**
     * Update a setting.
     */
    public function update($id, array $data): Setting
    {
        $setting = $this->findOrFail($id);
        $setting->update($data);

        return $setting->fresh();
    }

    /**
     * Delete a setting.
     */
    public function delete($id): bool
    {
        $setting = $this->findOrFail($id);

        return $setting->delete();
    }
}
