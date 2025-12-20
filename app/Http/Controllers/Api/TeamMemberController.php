<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\TeamMemberServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Http\Resources\TeamMemberResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected TeamMemberServiceInterface $teamMemberService
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
                'search',
            ]);

            $members = $this->teamMemberService->paginate($perPage, $filters);

            return $this->successResponse(
                TeamMemberResource::collection($members)->response()->getData(true),
                'Team members retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve team members: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamMemberRequest $request): JsonResponse
    {
        try {
            $member = $this->teamMemberService->create($request->validated());

            return $this->successResponse(
                new TeamMemberResource($member),
                'Team member created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create team member: ' . $e->getMessage(),
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
            $member = $this->teamMemberService->findById($id);

            if (!$member) {
                return $this->notFoundResponse('Team member not found');
            }

            return $this->successResponse(
                new TeamMemberResource($member),
                'Team member retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve team member: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamMemberRequest $request, string $id): JsonResponse
    {
        try {
            $member = $this->teamMemberService->findById($id);

            if (!$member) {
                return $this->notFoundResponse('Team member not found');
            }

            $member = $this->teamMemberService->update($id, $request->validated());

            return $this->successResponse(
                new TeamMemberResource($member),
                'Team member updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update team member: ' . $e->getMessage(),
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
            $member = $this->teamMemberService->findById($id);

            if (!$member) {
                return $this->notFoundResponse('Team member not found');
            }

            $this->teamMemberService->delete($id);

            return $this->successResponse(
                null,
                'Team member deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete team member: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Publish a team member.
     */
    public function publish(string $id): JsonResponse
    {
        try {
            $member = $this->teamMemberService->findById($id);

            if (!$member) {
                return $this->notFoundResponse('Team member not found');
            }

            $member = $this->teamMemberService->publish($id);

            return $this->successResponse(
                new TeamMemberResource($member),
                'Team member published successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to publish team member: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Unpublish a team member.
     */
    public function unpublish(string $id): JsonResponse
    {
        try {
            $member = $this->teamMemberService->findById($id);

            if (!$member) {
                return $this->notFoundResponse('Team member not found');
            }

            $member = $this->teamMemberService->unpublish($id);

            return $this->successResponse(
                new TeamMemberResource($member),
                'Team member unpublished successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to unpublish team member: ' . $e->getMessage(),
                500
            );
        }
    }
}
