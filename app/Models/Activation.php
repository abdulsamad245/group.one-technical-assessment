<?php

namespace App\Models;

use App\Constants\ActivationConstant;
use App\Enums\ActivationStatus;
use App\Scopes\ActivationBrandScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Activation extends Model
{
    use HasFactory;
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = ActivationConstant::TABLE;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new ActivationBrandScope());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        ActivationConstant::LICENSE_ID,
        ActivationConstant::DEVICE_IDENTIFIER,
        ActivationConstant::DEVICE_NAME,
        ActivationConstant::INSTANCE_TYPE,
        ActivationConstant::INSTANCE_VALUE,
        ActivationConstant::IP_ADDRESS,
        ActivationConstant::USER_AGENT,
        ActivationConstant::STATUS,
        ActivationConstant::ACTIVATED_AT,
        ActivationConstant::DEACTIVATED_AT,
        ActivationConstant::LAST_CHECKED_AT,
        ActivationConstant::METADATA,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        ActivationConstant::STATUS => ActivationStatus::class,
        ActivationConstant::ACTIVATED_AT => 'datetime',
        ActivationConstant::DEACTIVATED_AT => 'datetime',
        ActivationConstant::LAST_CHECKED_AT => 'datetime',
        ActivationConstant::METADATA => 'array',
        ActivationConstant::CREATED_AT => 'datetime',
        ActivationConstant::UPDATED_AT => 'datetime',
        ActivationConstant::DEVICE_IDENTIFIER => 'encrypted',
        ActivationConstant::IP_ADDRESS => 'encrypted',
    ];

    /**
     * Get the license that owns the activation.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Get the license key through the license relationship.
     */
    public function licenseKey(): HasOneThrough
    {
        return $this->hasOneThrough(
            LicenseKey::class,
            License::class,
            ActivationConstant::ID,
            ActivationConstant::ID,
            ActivationConstant::LICENSE_ID,
            ActivationConstant::LICENSE_KEY_ID
        );
    }

    /**
     * Scope a query to only include active activations.
     */
    public function scopeActive($query)
    {
        return $query->where(ActivationConstant::STATUS, ActivationStatus::ACTIVE);
    }

    /**
     * Deactivate this activation.
     */
    public function deactivate(): void
    {
        $this->update([
            ActivationConstant::STATUS => ActivationStatus::INACTIVE,
            ActivationConstant::DEACTIVATED_AT => now(),
        ]);
    }

    /**
     * Update last checked timestamp.
     */
    public function updateLastChecked(): void
    {
        $this->update([ActivationConstant::LAST_CHECKED_AT => now()]);
    }
}