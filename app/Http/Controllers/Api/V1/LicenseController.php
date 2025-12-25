<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\License\LicenseNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RenewLicenseRequest;
use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Http\Resources\LicenseResource;
use App\Services\LicenseService;
use Illuminate\Http\JsonResponse;

/**
 * License Controller
 *
 * Handles license management operations.
 * API documentation is located in App\Docs\LicenseDocs.
 */
class LicenseController extends Controller
{
    public function __construct(
        private readonly LicenseService $licenseService
    ) {
    }

    /**
     * List all licenses.
     */
    public function index(): JsonResponse
    {
        $licenses = $this->licenseService->getPaginatedLicenses();

        return $this->paginatedResponse($licenses, 'messages.licenses_found');
    }

    /**
     * Create a new license.
     */
    public function store(StoreLicenseRequest $request): JsonResponse
    {
        $dto = $request->createLicenseDTO();
        $result = $this->licenseService->createLicense($dto);

        return $this->created($result, 'messages.license_created');
    }

    /**
     * Get a specific license.
     */
    public function show(string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        return $this->success(
            ['license' => new LicenseResource($license)],
            'messages.license_found'
        );
    }

    /**
     * Update a license.
     */
    public function update(UpdateLicenseRequest $request, string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        $dto = $request->updateLicenseDTO();
        $updated = $this->licenseService->updateLicense($license, $dto->toArray());

        return $this->success(
            ['license' => new LicenseResource($updated->load('brand'))],
            'messages.license_updated'
        );
    }

    /**
     * Renew a license.
     */
    public function renew(RenewLicenseRequest $request, string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        $days = $request->getDays();
        $renewed = $this->licenseService->renewLicense($license, $days);

        return $this->success(
            ['license' => new LicenseResource($renewed->load('brand'))],
            'messages.license_renewed'
        );
    }

    /**
     * Suspend a license.
     */
    public function suspend(string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        $suspended = $this->licenseService->suspendLicense($license);

        return $this->success(
            ['license' => new LicenseResource($suspended->load('brand'))],
            'messages.license_suspended'
        );
    }

    /**
     * Resume a license.
     */
    public function resume(string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        $resumed = $this->licenseService->reactivateLicense($license);

        return $this->success(
            ['license' => new LicenseResource($resumed->load('brand'))],
            'messages.license_resumed'
        );
    }

    /**
     * Cancel a license.
     */
    public function cancel(string $id): JsonResponse
    {
        $license = $this->licenseService->getLicenseById($id);

        if (! $license) {
            throw new LicenseNotFoundException();
        }

        $canceled = $this->licenseService->updateLicense($license, ['status' => 'cancelled']);

        return $this->success(
            ['license' => new LicenseResource($canceled->load('brand'))],
            'messages.license_canceled'
        );
    }
}
