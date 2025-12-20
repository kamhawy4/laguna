<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\TestimonialServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function __construct(protected TestimonialServiceInterface $service)
    {
    }

    /**
     * Get all testimonials with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->integer('per_page', 15);
            $filters = $request->only(['status', 'is_featured', 'rating', 'search']);

            $testimonials = $this->service->paginate(
                perPage: $perPage,
                filters: $filters
            );

            return $this->successResponse(
                TestimonialResource::collection($testimonials)->response()->getData(true),
                'Testimonials retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve testimonials: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(StoreTestimonialRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $testimonial = $this->service->create($data);

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create testimonial: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Show the specified testimonial.
     */
    public function show($id): JsonResponse
    {
        try {
            $testimonial = $this->service->findOrFail($id);

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->notFoundResponse('Testimonial not found');
        }
    }

    /**
     * Update the specified testimonial.
     */
    public function update(UpdateTestimonialRequest $request, $id): JsonResponse
    {
        try {
            $testimonial = $this->service->update($id, $request->validated());

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update testimonial: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete the specified testimonial.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return $this->successResponse(
                null,
                'Testimonial deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete testimonial: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish a testimonial.
     */
    public function publish($id): JsonResponse
    {
        try {
            $testimonial = $this->service->publish($id);

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish testimonial: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish a testimonial.
     */
    public function unpublish($id): JsonResponse
    {
        try {
            $testimonial = $this->service->unpublish($id);

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish testimonial: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured($id): JsonResponse
    {
        try {
            $testimonial = $this->service->toggleFeatured($id);

            return $this->successResponse(
                new TestimonialResource($testimonial),
                'Testimonial featured status toggled successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to toggle featured status: ' . $e->getMessage(),
                500
            );
        }
    }
}
