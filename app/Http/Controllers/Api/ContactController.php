<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\ContactServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ContactServiceInterface $contactService
    ) {
    }

    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->integer('per_page', 15);
            $filters = $request->only([
                'status',
                'search',
                'email',
            ]);

            $contacts = $this->contactService->paginate($perPage, $filters);

            return $this->successResponse(
                ContactResource::collection($contacts)->response()->getData(true),
                'Contact messages retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve contact messages: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created contact message.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
            $contact = $this->contactService->create($request->validated());

            return $this->successResponse(
                new ContactResource($contact),
                'Thank you for your message! We will get back to you soon.',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to submit message: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Display the specified contact message.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $contact = $this->contactService->find($id);

            if (!$contact) {
                return $this->notFoundResponse('Contact message not found');
            }

            // Mark as read when viewed
            if ($contact->status === 'new') {
                $contact = $this->contactService->markAsRead($id);
            }

            return $this->successResponse(
                new ContactResource($contact),
                'Contact message retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve contact message: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Update the status of a contact message.
     */
    public function update(UpdateContactRequest $request, string $id): JsonResponse
    {
        try {
            $contact = $this->contactService->update($id, $request->validated());

            return $this->successResponse(
                new ContactResource($contact),
                'Contact message status updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update contact message: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete a contact message.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->contactService->delete($id);

            return $this->successResponse(
                null,
                'Contact message deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to delete contact message: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Mark a contact as read.
     */
    public function markAsRead(string $id): JsonResponse
    {
        try {
            $contact = $this->contactService->markAsRead($id);

            return $this->successResponse(
                new ContactResource($contact),
                'Contact message marked as read'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to mark as read: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Mark a contact as closed.
     */
    public function markAsClosed(string $id): JsonResponse
    {
        try {
            $contact = $this->contactService->markAsClosed($id);

            return $this->successResponse(
                new ContactResource($contact),
                'Contact message marked as closed'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to mark as closed: ' . $e->getMessage(),
                500
            );
        }
    }
}
