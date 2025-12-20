<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ServiceServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ServiceServiceInterface $serviceService
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
                'search',
            ]);

            $services = $this->serviceService->paginate($perPage, $filters);

            return $this->successResponse(
                ServiceResource::collection($services)->response()->getData(true),
                'Services retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve services: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $service = $this->serviceService->create($request->validated());

            return $this->successResponse(
                new ServiceResource($service),
                'Service created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create service: ' . $e->getMessage(),
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
            $service = $this->serviceService->findById($id);

            if (!$service) {
                return $this->notFoundResponse('Service not found');
            }

            return $this->successResponse(
                new ServiceResource($service),
                'Service retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve service: ' . $e->getMessage(),
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
            $locale = $request->input('locale') ?? app()->getLocale();
            $service = $this->serviceService->findBySlug($slug, $locale);

            if (!$service) {
                return $this->notFoundResponse('Service not found');
            }

            return $this->successResponse(
                new ServiceResource($service),
                'Service retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve service: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, string $id): JsonResponse
    {
        try {
            $service = $this->serviceService->update($id, $request->validated());

            return $this->successResponse(
                new ServiceResource($service),
                'Service updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update service: ' . $e->getMessage(),
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
            $this->serviceService->delete($id);

            return $this->successResponse(
                null,
                'Service deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete service: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish a service.
     */
    public function publish(string $id): JsonResponse
    {
        try {
            $service = $this->serviceService->publish($id);

            return $this->successResponse(
                new ServiceResource($service),
                'Service published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish service: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish a service.
     */
    public function unpublish(string $id): JsonResponse
    {
        try {
            $service = $this->serviceService->unpublish($id);

            return $this->successResponse(
                new ServiceResource($service),
                'Service unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish service: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Toggle featured status of a service.
     */
    public function toggleFeatured(string $id): JsonResponse
    {
        try {
            $service = $this->serviceService->toggleFeatured($id);

            return $this->successResponse(
                new ServiceResource($service),
                'Service featured status toggled successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to toggle featured status: ' . $e->getMessage(),
                500
            );
        }
    }
}
