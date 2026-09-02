<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Standard success response
     */
    public static function success(mixed $data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data ?? (object) [],
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Standard paginated response
     */
    public static function paginated(mixed $resource, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        if ($resource instanceof ResourceCollection && $resource->resource instanceof LengthAwarePaginator) {
            $paginator = $resource->resource;
            $data = $resource->resolve();
        } elseif ($resource instanceof LengthAwarePaginator) {
            $paginator = $resource;
            $data = $resource->items();
        } else {
            return self::success($resource, $message, $statusCode);
        }

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Standard error response
     */
    public static function error(string $message = 'Something went wrong.', int $statusCode = 500, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response
     */
    public static function validationError(mixed $errors, string $message = 'Validation failed.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Unauthorized / Unauthenticated error response
     */
    public static function unauthorized(string $message = 'Unauthenticated.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    /**
     * Forbidden error response
     */
    public static function forbidden(string $message = 'This action is unauthorized.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }

    /**
     * Not found error response
     */
    public static function notFound(string $message = 'Resource not found.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    /**
     * Too many requests error response
     */
    public static function tooManyRequests(string $message = 'Too many requests. Please try again later.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 429);
    }
}
