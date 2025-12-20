<?php

namespace App\Services;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Services\SettingServiceInterface;
use App\Models\Setting;

class SettingService implements SettingServiceInterface
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {
    }

    /**
     * Get the first (and usually only) setting record.
     */
    public function first(): ?Setting
    {
        return $this->repository->first();
    }

    /**
     * Get a setting by ID.
     */
    public function find($id): ?Setting
    {
        return $this->repository->find($id);
    }

    /**
     * Find a setting by ID or fail.
     */
    public function findOrFail($id): Setting
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Update settings.
     */
    public function update($id, array $data): Setting
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Toggle settings status.
     */
    public function toggleStatus($id): Setting
    {
        $setting = $this->findOrFail($id);
        $setting->status = !$setting->status;
        $setting->save();

        return $setting;
    }
}
