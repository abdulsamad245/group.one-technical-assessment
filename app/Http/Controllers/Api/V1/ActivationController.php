<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\License\ActivationNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateLicenseRequest;
use App\Http\Requests\CheckActivationStatusRequest;
use App\Http\Requests\DeactivateLicenseRequest;
use App\Http\Resources\ActivationResource;
use App\Services\ActivationService;
use Illuminate\Http\JsonResponse;

/**
 * Activation Controller
 *
 * Handles license activation operations.
 * API documentation is located in App\Docs\ActivationDocs.
 */
class ActivationController extends Controller
{
    public function __construct(
        private readonly ActivationService $activationService
    ) {
    }

    /**
     * Activate a license.
     */
    public function store(ActivateLicenseRequest $request): JsonResponse
    {
        $dto = $request->createActivationDTO();
        $activation = $this->activationService->activate($dto);

        return $this->created(
            ['activation' => new ActivationResource($activation->load(['licenseKey', 'license']))],
            'messages.license_activated'
        );
    }

    /**
     * Deactivate a license.
     */
    public function deactivate(DeactivateLicenseRequest $request): JsonResponse
    {
        $dto = $request->createDeactivationDTO();
        $activation = $this->activationService->getActivationById($dto->getActivationId());

        if (! $activation) {
            throw new ActivationNotFoundException();
        }

        $this->activationService->deactivate($activation);

        return $this->success([], 'messages.license_deactivated');
    }

    /**
     * Check activation status.
     */
    public function status(CheckActivationStatusRequest $request): JsonResponse
    {
        $dto = $request->createCheckStatusDTO();
        $status = $this->activationService->checkStatus(
            $dto->getLicenseKey(),
            $dto->getProductSlug()
        );

        return $this->success($status, 'messages.activation_status_checked');
    }
}
