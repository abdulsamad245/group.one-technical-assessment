<?php

namespace App\Models;

use App\Constants\LicenseKeyConstant;
use App\Enums\ActivationStatus;
use App\Enums\LicenseKeyStatus;
use App\Scopes\LicenseKeyBrandScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenseKey extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = LicenseKeyConstant::TABLE;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new LicenseKeyBrandScope());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        LicenseKeyConstant::ID,
        LicenseKeyConstant::BRAND_ID,
        LicenseKeyConstant::CUSTOMER_EMAIL,
        LicenseKeyConstant::KEY,
        LicenseKeyConstant::KEY_HASH,
        LicenseKeyConstant::STATUS,
        LicenseKeyConstant::EXPIRES_AT,
        LicenseKeyConstant::METADATA,
    ];

    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $encryptable = [
        LicenseKeyConstant::KEY,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        LicenseKeyConstant::EXPIRES_AT => 'datetime',
        LicenseKeyConstant::CREATED_AT => 'datetime',
        LicenseKeyConstant::UPDATED_AT => 'datetime',
        LicenseKeyConstant::KEY => 'encrypted',
        LicenseKeyConstant::STATUS => LicenseKeyStatus::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        LicenseKeyConstant::STATUS => 'active',
    ];

    /**
     * Get the brand that owns the license key.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all licenses associated with this license key.
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    /**
     * Get the activations for the license key through licenses.
     */
    public function activations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Activation::class,
            License::class,
            'license_key_id', 
            'license_id',     
            'id',             
            'id'             
        );
    }

    /**
     * Get active activations through licenses.
     */
    public function activeActivations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Activation::class,
            License::class,
            'license_key_id', 
            'license_id',     
            'id',             
            'id'             
        )->where('activations.status', ActivationStatus::ACTIVE);
    }

    /**
     * Scope a query to only include active license keys.
     */
    public function scopeActive($query)
    {
        return $query->where(LicenseKeyConstant::STATUS, LicenseKeyStatus::ACTIVE);
    }

    /**
     * Check if the license key is valid.
     */
    public function isValid(): bool
    {
        return $this->{LicenseKeyConstant::STATUS} === LicenseKeyStatus::ACTIVE
            && (! $this->{LicenseKeyConstant::EXPIRES_AT} || $this->{LicenseKeyConstant::EXPIRES_AT}->isFuture());
    }
}