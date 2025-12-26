<?php

namespace App\Models;

use App\Constants\LicenseConstant;
use App\Enums\LicenseStatus;
use App\Enums\LicenseType;
use App\Scopes\BrandScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = LicenseConstant::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        LicenseConstant::ID,
        LicenseConstant::LICENSE_KEY_ID,
        LicenseConstant::BRAND_ID,
        LicenseConstant::CUSTOMER_EMAIL,
        LicenseConstant::CUSTOMER_NAME,
        LicenseConstant::PRODUCT_NAME,
        LicenseConstant::PRODUCT_SLUG,
        LicenseConstant::PRODUCT_SKU,
        LicenseConstant::LICENSE_TYPE,
        LicenseConstant::MAX_ACTIVATIONS_PER_INSTANCE,
        LicenseConstant::CURRENT_ACTIVATIONS,
        LicenseConstant::EXPIRES_AT,
        LicenseConstant::STATUS,
        LicenseConstant::METADATA,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        LicenseConstant::MAX_ACTIVATIONS_PER_INSTANCE => 'array',
        LicenseConstant::CURRENT_ACTIVATIONS => 'integer',
        LicenseConstant::EXPIRES_AT => 'datetime',
        LicenseConstant::METADATA => 'array',
        LicenseConstant::CREATED_AT => 'datetime',
        LicenseConstant::UPDATED_AT => 'datetime',
        LicenseConstant::DELETED_AT => 'datetime',
        LicenseConstant::CUSTOMER_EMAIL => 'encrypted',
        LicenseConstant::STATUS => LicenseStatus::class,
        LicenseConstant::LICENSE_TYPE => LicenseType::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        LicenseConstant::CURRENT_ACTIVATIONS => 0,
        LicenseConstant::STATUS => 'active',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new BrandScope());
    }

    /**
     * Get the brand that owns the license.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the license key that owns the license.
     */
    public function licenseKey(): BelongsTo
    {
        return $this->belongsTo(LicenseKey::class);
    }

    /**
     * Get the events for the license.
     */
    public function events(): HasMany
    {
        return $this->hasMany(LicenseEvent::class);
    }

    /**
     * Scope a query to only include active licenses.
     */
    public function scopeActive($query)
    {
        return $query->where(LicenseConstant::STATUS, LicenseStatus::ACTIVE);
    }

    /**
     * Scope a query to filter by customer email.
     */
    public function scopeByCustomerEmail($query, string $email)
    {
        return $query->where(LicenseConstant::CUSTOMER_EMAIL, $email);
    }

    /**
     * Check if the license is expired.
     */
    public function isExpired(): bool
    {
        if ($this->{LicenseConstant::LICENSE_TYPE} === LicenseType::PERPETUAL) {
            return false;
        }

        $expiresAt = $this->{LicenseConstant::EXPIRES_AT};
        return $expiresAt && $expiresAt->isPast();
    }

    /**
     * Check if the license can be activated.
     * Note: This is a basic check. Instance-specific limits are checked in ActivationService.
     */
    public function canActivate(): bool
    {
        return $this->{LicenseConstant::STATUS} === LicenseStatus::ACTIVE
            && ! $this->isExpired();
    }

    /**
     * Increment the activation count.
     */
    public function incrementActivations(): void
    {
        $this->increment(LicenseConstant::CURRENT_ACTIVATIONS);
    }

    /**
     * Decrement the activation count.
     */
    public function decrementActivations(): void
    {
        $this->decrement(LicenseConstant::CURRENT_ACTIVATIONS);
    }
}