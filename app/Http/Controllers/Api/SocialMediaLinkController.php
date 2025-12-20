<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\SocialMediaLinkServiceInterface;
use App\Http\Requests\StoreSocialMediaLinkRequest;
use App\Http\Requests\UpdateSocialMediaLinkRequest;
use App\Http\Resources\SocialMediaLinkResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SocialMediaLinkController
{
    use ApiResponse;

    public function __construct(protected SocialMediaLinkServiceInterface $service)
    {
    }

    /**
     * Get all active social media links.
     */
    public function index(): JsonResponse
    {
        $filters = request()->only(['is_active', 'platform', 'search']);

        $links = $this->service->paginate(
            perPage: (int) request('per_page', 50),
            filters: $filters
        );

        return $this->successResponse(SocialMediaLinkResource::collection($links)->response()->getData(true));
    }

    /**
     * Store a newly created social media link.
     */
    public function store(StoreSocialMediaLinkRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Set default icon if not provided
        if (!isset($data['icon'])) {
            $platformIcons = \App\Models\SocialMediaLink::getPlatformsWithIcons();
            $data['icon'] = $platformIcons[$data['platform']] ?? 'fab fa-link';
        }

        $link = $this->service->create($data);

        return $this->successCreated(new SocialMediaLinkResource($link));
    }

    /**
     * Show the specified social media link.
     */
    public function show($id): JsonResponse
    {
        $link = $this->service->findOrFail($id);

        return $this->successResponse(new SocialMediaLinkResource($link));
    }

    /**
     * Update the specified social media link.
     */
    public function update(UpdateSocialMediaLinkRequest $request, $id): JsonResponse
    {
        $link = $this->service->update($id, $request->validated());

        return $this->successResponse(new SocialMediaLinkResource($link));
    }

    /**
     * Delete the specified social media link.
     */
    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);

        return $this->successDeleted();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive($id): JsonResponse
    {
        $link = $this->service->toggleActive($id);

        return $this->successResponse(new SocialMediaLinkResource($link));
    }

    /**
     * Reorder social media links.
     */
    public function reorder(): JsonResponse
    {
        $data = request()->validate([
            'links' => ['required', 'array'],
            'links.*.id' => ['required', 'integer'],
            'links.*.order' => ['required', 'integer', 'min:0'],
        ]);

        $this->service->reorder($data['links']);

        return $this->successResponse(['message' => 'Links reordered successfully']);
    }
}
