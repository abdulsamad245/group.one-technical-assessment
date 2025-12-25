<?php

namespace Tests\Unit\Services;

use App\DTOs\CreateActivationDTO;
use App\Enums\ActivationStatus;
use App\Enums\LicenseKeyStatus;
use App\Enums\LicenseStatus;
use App\Models\Activation;
use App\Models\Brand;
use App\Models\License;
use App\Models\LicenseKey;
use App\Services\ActivationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ActivationService $service;
    protected Brand $brand;
    protected LicenseKey $licenseKey;
    protected License $license;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ActivationService::class);
        $this->brand = Brand::factory()->create();
        $this->licenseKey = LicenseKey::factory()->create([
            'brand_id' => $this->brand->id,
            'status' => LicenseKeyStatus::ACTIVE,
        ]);
        $this->license = License::factory()->create([
            'brand_id' => $this->brand->id,
            'license_key_id' => $this->licenseKey->id,
            'status' => LicenseStatus::ACTIVE,
            'max_activations_per_instance' => ['site_url' => 2, 'machine_id' => 1],
            'expires_at' => now()->addYear(),
        ]);
    }

    private function createActivationDTO(string $instanceType, string $instanceValue): CreateActivationDTO
    {
        return (new CreateActivationDTO())
            ->setLicenseKey($this->licenseKey->key)
            ->setProductSlug($this->license->product_slug)
            ->setInstanceType($instanceType)
            ->setInstanceValue($instanceValue);
    }

    public function test_creates_activation_successfully(): void
    {
        $dto = $this->createActivationDTO('site_url', 'https://example.com');

        $activation = $this->service->activate($dto);

        $this->assertNotNull($activation);
        $this->assertEquals(ActivationStatus::ACTIVE, $activation->status);
        $this->assertEquals('https://example.com', $activation->instance_value);
    }

    public function test_returns_existing_activation_for_same_instance(): void
    {
        $dto = $this->createActivationDTO('site_url', 'https://example.com');

        $first = $this->service->activate($dto);
        $second = $this->service->activate($dto);

        $this->assertEquals($first->id, $second->id);
        $this->assertDatabaseCount('activations', 1);
    }

    public function test_throws_exception_when_seat_limit_exceeded(): void
    {
        // Use both seats
        for ($i = 1; $i <= 2; $i++) {
            $dto = $this->createActivationDTO('site_url', "https://site{$i}.com");
            $this->service->activate($dto);
        }

        $this->expectException(\App\Exceptions\License\MaxActivationsReachedException::class);
        $this->expectExceptionMessage('Maximum activations reached');

        $dto = $this->createActivationDTO('site_url', 'https://site3.com');
        $this->service->activate($dto);
    }

    public function test_throws_exception_for_expired_license(): void
    {
        // Ensure license type is SUBSCRIPTION so it can expire (PERPETUAL never expires)
        $this->license->update([
            'license_type' => \App\Enums\LicenseType::SUBSCRIPTION,
            'expires_at' => now()->subDay(),
        ]);

        $this->expectException(\App\Exceptions\License\LicenseCannotActivateException::class);
        $this->expectExceptionMessage('cannot be activated');

        $dto = $this->createActivationDTO('site_url', 'https://example.com');
        $this->service->activate($dto);
    }

    public function test_throws_exception_for_suspended_license(): void
    {
        $this->license->update(['status' => LicenseStatus::SUSPENDED]);

        $this->expectException(\App\Exceptions\License\LicenseCannotActivateException::class);
        $this->expectExceptionMessage('cannot be activated');

        $dto = $this->createActivationDTO('site_url', 'https://example.com');
        $this->service->activate($dto);
    }

    public function test_deactivates_activation(): void
    {
        $activation = Activation::factory()->create([
            'license_id' => $this->license->id,
            'status' => ActivationStatus::ACTIVE,
        ]);

        $this->service->deactivate($activation);

        $activation->refresh();
        $this->assertEquals(ActivationStatus::INACTIVE, $activation->status);
        $this->assertNotNull($activation->deactivated_at);
    }

    public function test_different_instance_types_have_separate_limits(): void
    {
        // Use both site_url seats
        for ($i = 1; $i <= 2; $i++) {
            $dto = $this->createActivationDTO('site_url', "https://site{$i}.com");
            $this->service->activate($dto);
        }

        // Should still work for machine_id
        $dto = $this->createActivationDTO('machine_id', 'MACHINE-001');
        $activation = $this->service->activate($dto);

        $this->assertNotNull($activation);
    }
}
