<?php

namespace App\Models;

use App\Constants\BrandConstant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = BrandConstant::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        BrandConstant::NAME,
        BrandConstant::SLUG,
        BrandConstant::DESCRIPTION,
        BrandConstant::CONTACT_EMAIL,
        BrandConstant::WEBSITE,
        BrandConstant::SETTINGS,
        BrandConstant::IS_ACTIVE,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        BrandConstant::SETTINGS => 'array',
        BrandConstant::IS_ACTIVE => 'boolean',
        BrandConstant::CREATED_AT => 'datetime',
        BrandConstant::UPDATED_AT => 'datetime',
        BrandConstant::DELETED_AT => 'datetime',
    ];

    /**
     * Get the licenses for the brand.
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    /**
     * Get the API keys for the brand.
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Scope a query to only include active brands.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(BrandConstant::IS_ACTIVE, true);
    }
}
