<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ProjectServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\AreaGuide;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ProjectServiceInterface $projectService
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
                'is_featured',
                'area',
                'property_type',
                'delivery_date_from',
                'delivery_date_to',
                'price_min',
                'price_max',
                'developer_name',
                'search',
            ]);

            $projects = $this->projectService->paginate($perPage, $filters);

            return $this->successResponse(
                ProjectResource::collection($projects)->response()->getData(true),
                'Projects retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve projects: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->create($request->validated());

            return $this->successResponse(
                new ProjectResource($project),
                'Project created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create project: ' . $e->getMessage(),
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
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            return $this->successResponse(
                new ProjectResource($project),
                'Project retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve project: ' . $e->getMessage(),
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
            $project = $this->projectService->findBySlug($slug, $locale);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            return $this->successResponse(
                new ProjectResource($project),
                'Project retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve project: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get projects by area guide.
     */
    public function byAreaGuide(string $areaGuideSlug, Request $request): JsonResponse
    {
        try {
            $perPage = $request->integer('per_page', 15);
            $locale = $request->get('locale', app()->getLocale());

            $areaGuide = AreaGuide::where(
                "slug->{$locale}",
                $areaGuideSlug
            )->first();

            if (!$areaGuide) {
                return $this->notFoundResponse('Area guide not found');
            }

            $projects = $areaGuide->projects()
                ->where('status', 'published')
                ->paginate($perPage);

            if ($projects->isEmpty()) {
                return $this->notFoundResponse('No projects found for this area guide');
            }

            return $this->successResponse(
                ProjectResource::collection($projects)->response()->getData(true),
                'Projects retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve projects: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get featured projects.
     */
    public function getFeatured(Request $request): JsonResponse
    {
        try {
            $perPage = $request->integer('per_page', 6);
            $limit = $request->integer('limit', null);

            $query = \App\Models\Project::where('is_featured', true)
                ->where('status', 'published')
                ->orderBy('sort_order', 'asc');

            if ($limit) {
                $projects = $query->limit($limit)->get();
            } else {
                $projects = $query->paginate($perPage);
            }

            return $this->successResponse(
                ProjectResource::collection($projects)->response()->getData(true),
                'Featured projects retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve featured projects: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id): JsonResponse
    {
        try {
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $project = $this->projectService->update($id, $request->validated());

            return $this->successResponse(
                new ProjectResource($project),
                'Project updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update project: ' . $e->getMessage(),
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
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $this->projectService->delete($id);

            return $this->successResponse(
                null,
                'Project deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete project: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish a project.
     */
    public function publish(string $id): JsonResponse
    {
        try {
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $project = $this->projectService->publish($id);

            return $this->successResponse(
                new ProjectResource($project),
                'Project published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish project: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish a project.
     */
    public function unpublish(string $id): JsonResponse
    {
        try {
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $project = $this->projectService->unpublish($id);

            return $this->successResponse(
                new ProjectResource($project),
                'Project unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish project: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(string $id): JsonResponse
    {
        try {
            $project = $this->projectService->findById($id);

            if (!$project) {
                return $this->notFoundResponse('Project not found');
            }

            $project = $this->projectService->toggleFeatured($id);

            return $this->successResponse(
                new ProjectResource($project),
                'Project featured status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update featured status: ' . $e->getMessage(),
                500
            );
        }
    }
}
