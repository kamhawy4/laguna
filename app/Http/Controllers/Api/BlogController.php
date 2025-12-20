<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\BlogServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected BlogServiceInterface $blogService
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
                'published_from',
                'published_to',
                'search',
            ]);

            $blogs = $this->blogService->paginate($perPage, $filters);

            return $this->successResponse(
                BlogResource::collection($blogs)->response()->getData(true),
                'Blogs retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve blogs: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        try {
            $blog = $this->blogService->create($request->validated());

            return $this->successResponse(
                new BlogResource($blog),
                'Blog created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create blog: ' . $e->getMessage(),
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
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            return $this->successResponse(
                new BlogResource($blog),
                'Blog retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve blog: ' . $e->getMessage(),
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
            $blog = $this->blogService->findBySlug($slug, $locale);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            return $this->successResponse(
                new BlogResource($blog),
                'Blog retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve blog: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, string $id): JsonResponse
    {
        try {
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            $blog = $this->blogService->update($id, $request->validated());

            return $this->successResponse(
                new BlogResource($blog),
                'Blog updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update blog: ' . $e->getMessage(),
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
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            $this->blogService->delete($id);

            return $this->successResponse(
                null,
                'Blog deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete blog: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish a blog.
     */
    public function publish(string $id): JsonResponse
    {
        try {
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            $blog = $this->blogService->publish($id);

            return $this->successResponse(
                new BlogResource($blog),
                'Blog published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish blog: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish a blog.
     */
    public function unpublish(string $id): JsonResponse
    {
        try {
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            $blog = $this->blogService->unpublish($id);

            return $this->successResponse(
                new BlogResource($blog),
                'Blog unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish blog: ' . $e->getMessage(),
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
            $blog = $this->blogService->findById($id);

            if (!$blog) {
                return $this->notFoundResponse('Blog not found');
            }

            $blog = $this->blogService->toggleFeatured($id);

            return $this->successResponse(
                new BlogResource($blog),
                'Blog featured status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update featured status: ' . $e->getMessage(),
                500
            );
        }
    }
}
