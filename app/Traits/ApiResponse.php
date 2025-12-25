<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Success response with translation support.
     *
     * @param  array<string, mixed>  $data  Response data
     * @param  string  $messageKey  Translation key for the message
     * @param  int  $status  HTTP status code
     * @param  array<string, mixed>  $params  Translation parameters
     */
    protected function success(
        array $data = [],
        string $messageKey = '',
        int $status = Response::HTTP_OK,
        array $params = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $messageKey ? __($messageKey, $params) : null,
            'data' => $data,
        ], $status);
    }

    /**
     * Error response with translation and error code support.
     *
     * @param  int  $status  HTTP status code
     * @param  string  $messageKey  Translation key for the message
     * @param  string  $code  Error code for API clients
     * @param  array<string, mixed>  $params  Translation parameters
     */
    protected function error(
        int $status,
        string $messageKey,
        string $code,
        array $params = []
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => __($messageKey, $params),
            ],
        ], $status);
    }

    /**
     * Paginated response with translation support.
     *
     * @param  mixed  $paginator  Laravel paginator instance
     * @param  string  $messageKey  Translation key for the message
     * @param  array<string, mixed>  $params  Translation parameters
     */
    protected function paginatedResponse(
        mixed $paginator,
        string $messageKey = '',
        array $params = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $messageKey ? __($messageKey, $params) : null,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ], Response::HTTP_OK);
    }

    /**
     * Created response (201) with translation support.
     *
     * @param  array<string, mixed>  $data  Response data
     * @param  string  $messageKey  Translation key for the message
     * @param  array<string, mixed>  $params  Translation parameters
     */
    protected function created(
        array $data = [],
        string $messageKey = '',
        array $params = []
    ): JsonResponse {
        return $this->success($data, $messageKey, Response::HTTP_CREATED, $params);
    }

    /**
     * No content response (204).
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
