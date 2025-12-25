<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApiKeyRequest;
use App\Services\ApiKeyService;
use Illuminate\Http\JsonResponse;

class ApiKeyController extends Controller
{
    public function __construct(
        private readonly ApiKeyService $apiKeyService
    ) {
    }

    /**
     * Get all API keys for the authenticated user's brand.
     */
    public function index(): JsonResponse
    {
        $brandId = auth()->user()->brand_id;
        $result = $this->apiKeyService->getApiKeysByBrand($brandId);

        return $this->success(['api_keys' => $result], 'messages.api_keys_retrieved');
    }

    /**
     * Create a new API key.
     */
    public function store(StoreApiKeyRequest $request): JsonResponse
    {
        $dto = $request->createDTO();
        $result = $this->apiKeyService->createApiKey($dto);

        return $this->created($result, 'messages.api_key_created');
    }

    /**
     * Rotate an API key.
     */
    public function rotate(string $id): JsonResponse
    {
        $result = $this->apiKeyService->rotateApiKey($id);

        return $this->success($result, 'messages.api_key_rotated');
    }

    /**
     * Revoke an API key.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->apiKeyService->revokeApiKey($id);

        return $this->success([], 'messages.api_key_cancelled');
    }
}
