<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetCustomerLicensesRequest;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;

/**
 * Customer Controller
 *
 * Handles customer lookup operations.
 */
class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerService $customerService
    ) {
    }

    /**
     * Get all licenses for a customer by email.
     */
    public function licenses(GetCustomerLicensesRequest $request): JsonResponse
    {
        $dto = $request->createCustomerLicensesDTO();
        $summary = $this->customerService->getCustomerSummary($dto->getEmail());

        return $this->success($summary, 'messages.customer_licenses_found');
    }
}
