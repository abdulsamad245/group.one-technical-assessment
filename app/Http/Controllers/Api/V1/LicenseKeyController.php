<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\License\LicenseKeyNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\LicenseKeyResource;
use App\Repositories\LicenseKeyRepository;
use Illuminate\Http\JsonResponse;

/**
 * License Key Controller
 *
 * Handles license key management operations.
 */
class LicenseKeyController extends Controller
{
    public function __construct(
        private readonly LicenseKeyRepository $licenseKeyRepository
    ) {
    }

    /**
     * List all license keys for the authenticated brand.
     */
    public function index(): JsonResponse
    {
        $licenseKeys = $this->licenseKeyRepository->getActive();

        return $this->success(
            ['license_keys' => LicenseKeyResource::collection($licenseKeys)],
            'messages.license_keys_found'
        );
    }

    /**
     * Get a specific license key with full details.
     * Shows status, entitlements, licenses, and remaining seats.
     */
    public function show(string $id): JsonResponse
    {
        $licenseKey = $this->licenseKeyRepository->findById($id);

        if (! $licenseKey) {
            throw new LicenseKeyNotFoundException();
        }

        // Load all relationships for full details
        $licenseKey->load(['licenses', 'activations', 'brand']);

        return $this->success(
            ['license_key' => new LicenseKeyResource($licenseKey)],
            'messages.license_key_found'
        );
    }

    /**
     * Get a license key by key string.
     * Shows status, entitlements, licenses, and remaining seats.
     */
    public function showByKey(string $key): JsonResponse
    {
        $licenseKey = $this->licenseKeyRepository->findByKey($key);

        if (! $licenseKey) {
            throw new LicenseKeyNotFoundException();
        }

        // Load all relationships for full details
        $licenseKey->load(['licenses', 'activations', 'brand']);

        return $this->success(
            ['license_key' => new LicenseKeyResource($licenseKey)],
            'messages.license_key_found'
        );
    }
}
