<?php

namespace App\Models;

use App\Constants\LicenseEventConstant;
use App\Scopes\LicenseEventBrandScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseEvent extends Model
{
    use HasFactory;
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = LicenseEventConstant::TABLE;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new LicenseEventBrandScope());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        LicenseEventConstant::LICENSE_ID,
        LicenseEventConstant::EVENT_TYPE,
        LicenseEventConstant::DESCRIPTION,
        LicenseEventConstant::EVENT_DATA,
        LicenseEventConstant::IP_ADDRESS,
        LicenseEventConstant::USER_AGENT,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        LicenseEventConstant::EVENT_DATA => 'array',
        LicenseEventConstant::CREATED_AT => 'datetime',
        LicenseEventConstant::UPDATED_AT => 'datetime',
    ];

    /**
     * Get the license that owns the event.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Scope a query to filter by event type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where(LicenseEventConstant::EVENT_TYPE, $type);
    }
}
