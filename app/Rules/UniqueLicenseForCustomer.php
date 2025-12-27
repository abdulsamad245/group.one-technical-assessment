<?php

namespace App\Rules;

use App\Models\License;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that a license does not already exist for the same
 * customer email, brand, and product combination.
 */
class UniqueLicenseForCustomer implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $customerEmail = $this->data['customer_email'] ?? null;
        $productSlug = $value;

        if (! $customerEmail || ! $productSlug) {
            return;
        }

        $existingLicense = License::where('product_slug', $productSlug)
            ->get()
            ->first(fn ($license) => strtolower($license->customer_email) === strtolower($customerEmail));

        if ($existingLicense) {
            /** @disregard P1005 Intelephense false positive */
            $fail(__('messages.license_already_exists_for_customer'));
        }
    }
}
