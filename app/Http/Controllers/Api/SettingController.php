<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\SettingServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SettingServiceInterface $settingService
    ) {
    }

    /**
     * Get the site settings.
     */
    public function index(): JsonResponse
    {
        try {
            $setting = $this->settingService->first();

            if (!$setting) {
                return $this->notFoundResponse('Settings not found');
            }

            return $this->successResponse(
                new SettingResource($setting),
                'Settings retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve settings: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store/Create settings.
     */
    public function store(StoreSettingRequest $request): JsonResponse
    {
        try {
            $setting = $this->settingService->first();

            if ($setting) {
                $setting = $this->settingService->update($setting->id, $request->validated());
            } else {
                $setting = $this->settingService->update(1, $request->validated());
            }

            return $this->successResponse(
                new SettingResource($setting),
                'Settings saved successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to save settings: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Show specific setting by ID.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $setting = $this->settingService->find($id);

            if (!$setting) {
                return $this->notFoundResponse('Setting not found');
            }

            return $this->successResponse(
                new SettingResource($setting),
                'Setting retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve setting: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update settings.
     */
    public function update(UpdateSettingRequest $request, string $id): JsonResponse
    {
        try {
            $setting = $this->settingService->update($id, $request->validated());

            return $this->successResponse(
                new SettingResource($setting),
                'Settings updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update settings: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Toggle settings status.
     */
    public function toggleStatus(string $id): JsonResponse
    {
        try {
            $setting = $this->settingService->toggleStatus($id);

            return $this->successResponse(
                new SettingResource($setting),
                'Settings status toggled successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to toggle settings status: ' . $e->getMessage(),
                500
            );
        }
    }
}
