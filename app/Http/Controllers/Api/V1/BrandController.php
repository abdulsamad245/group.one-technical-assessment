<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\Brand\BrandNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;

/**
 * Brand Controller
 *
 * Handles brand (tenant) management operations.
 * API documentation is located in App\Docs\BrandDocs.
 */
class BrandController extends Controller
{
    public function __construct(
        private readonly BrandService $brandService
    ) {
    }

    /**
     * List all brands.
     */
    public function index(): JsonResponse
    {
        $brands = $this->brandService->getAllBrands();

        return $this->success(
            ['brands' => BrandResource::collection($brands)],
            'messages.brands_found'
        );
    }

    /**
     * Create a new brand.
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        $dto = $request->createBrandDTO();
        $brand = $this->brandService->createBrand($dto);

        return $this->created(
            ['brand' => new BrandResource($brand)],
            'messages.brand_created'
        );
    }

    /**
     * Get a specific brand.
     */
    public function show(string $id): JsonResponse
    {
        $brand = $this->brandService->getBrandById((int) $id);

        if (! $brand) {
            throw new BrandNotFoundException();
        }

        return $this->success(
            ['brand' => new BrandResource($brand)],
            'messages.brand_found'
        );
    }

    /**
     * Update a brand.
     */
    public function update(UpdateBrandRequest $request, string $id): JsonResponse
    {
        $brand = $this->brandService->getBrandById((int) $id);

        if (! $brand) {
            throw new BrandNotFoundException();
        }

        $dto = $request->createUpdateBrandDTO();
        $updated = $this->brandService->updateBrand($brand, $dto->toArray());

        return $this->success(
            ['brand' => new BrandResource($updated)],
            'messages.brand_updated'
        );
    }

    /**
     * Delete a brand.
     */
    public function destroy(string $id): JsonResponse
    {
        $brand = $this->brandService->getBrandById((int) $id);

        if (! $brand) {
            throw new BrandNotFoundException();
        }

        $this->brandService->deleteBrand($brand);

        return $this->success([], 'messages.brand_deleted');
    }
}
