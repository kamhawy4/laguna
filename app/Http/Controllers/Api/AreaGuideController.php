<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\AreaGuideServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAreaGuideRequest;
use App\Http\Requests\UpdateAreaGuideRequest;
use App\Http\Resources\AreaGuideResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AreaGuideController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AreaGuideServiceInterface $areaGuideService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->integer('per_page', 15);
            $filters = $request->only([
                'status',
                'is_popular',
                'search',
            ]);

            $guides = $this->areaGuideService->paginate($perPage, $filters, ['projects']);

            return $this->successResponse(
                AreaGuideResource::collection($guides)->response()->getData(true),
                'Area guides retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve area guides: ' . $e->getMessage(),
                500
            );
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id, ['projects']);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified resource by slug.
     */
    public function showBySlug(string $slug, Request $request): JsonResponse
    {
        try {
            $locale = $request->get('locale', app()->getLocale());
            $guide = $this->areaGuideService->findBySlug($slug, $locale, ['projects']);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAreaGuideRequest $request, string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $data = $request->validated();
            $projectIds = $data['project_ids'] ?? [];
            unset($data['project_ids']);

            $guide = $this->areaGuideService->update($id, $data);

            if (isset($request->project_ids)) {
                $this->areaGuideService->syncProjects($id, $projectIds);
                $guide->load('projects');
            }

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $this->areaGuideService->delete($id);

            return $this->successResponse(
                null,
                'Area guide deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish an area guide.
     */
    public function publish(string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $guide = $this->areaGuideService->publish($id);

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish an area guide.
     */
    public function unpublish(string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $guide = $this->areaGuideService->unpublish($id);

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish area guide: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Toggle popular status.
     */
    public function togglePopular(string $id): JsonResponse
    {
        try {
            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $guide = $this->areaGuideService->togglePopular($id);

            return $this->successResponse(
                new AreaGuideResource($guide),
                'Area guide popular status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update popular status: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Sync projects for an area guide.
     */
    public function syncProjects(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'project_ids' => ['required', 'array'],
                'project_ids.*' => ['integer', 'exists:projects,id'],
            ]);

            $guide = $this->areaGuideService->findById($id);

            if (!$guide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $guide = $this->areaGuideService->syncProjects($id, $request->project_ids);

            return $this->successResponse(
                new AreaGuideResource($guide->load('projects')),
                'Projects synced successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to sync projects: ' . $e->getMessage(),
                500
            );
        }
    }
}
