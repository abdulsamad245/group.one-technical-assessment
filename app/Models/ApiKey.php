<?php

namespace App\Models;

use App\Constants\ApiKeyConstant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * API Key Model
 *
 * Represents an API key associated with a brand (tenant) for authentication.
 *
 * @property string $id
 * @property string $brand_id
 * @property string $name
 * @property string $key
 * @property string $prefix
 * @property array|null $permissions
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Brand|null $brand
 */
class ApiKey extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = ApiKeyConstant::TABLE;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        ApiKeyConstant::ID,
        ApiKeyConstant::BRAND_ID,
        ApiKeyConstant::NAME,
        ApiKeyConstant::KEY,
        ApiKeyConstant::PREFIX,
        ApiKeyConstant::PERMISSIONS,
        ApiKeyConstant::LAST_USED_AT,
        ApiKeyConstant::EXPIRES_AT,
        ApiKeyConstant::IS_ACTIVE,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        ApiKeyConstant::PERMISSIONS => 'array',
        ApiKeyConstant::LAST_USED_AT => 'datetime',
        ApiKeyConstant::EXPIRES_AT => 'datetime',
        ApiKeyConstant::IS_ACTIVE => 'boolean',
        ApiKeyConstant::CREATED_AT => 'datetime',
        ApiKeyConstant::UPDATED_AT => 'datetime',
        ApiKeyConstant::DELETED_AT => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        ApiKeyConstant::KEY,
    ];

    /**
     * Get the brand that owns the API key.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Generate a new API key.
     *
     * @return string The plain text API key (prefix.secret)
     */
    public static function generate(): string
    {
        $prefix = 'lcs_' . Str::random(8);
        $secret = Str::random(40);

        return $prefix . '.' . $secret;
    }

    /**
     * Hash the API key for storage.
     *
     * @param  string  $plainKey  The plain text API key
     * @return string The hashed key
     */
    public static function hash(string $plainKey): string
    {
        return hash('sha256', $plainKey);
    }

    /**
     * Extract the prefix from a plain API key.
     *
     * @param  string  $plainKey  The plain text API key
     * @return string The prefix
     */
    public static function extractPrefix(string $plainKey): string
    {
        return explode('.', $plainKey)[0] ?? '';
    }

    /**
     * Check if the API key is valid (active and not expired).
     */
    public function isValid(): bool
    {
        if (! $this->{ApiKeyConstant::IS_ACTIVE}) {
            return false;
        }

        $expiresAt = $this->{ApiKeyConstant::EXPIRES_AT};
        if ($expiresAt && $expiresAt->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Update the last used timestamp.
     */
    public function markAsUsed(): void
    {
        $this->{ApiKeyConstant::LAST_USED_AT} = now();
        $this->save();
    }

    /**
     * Scope a query to only include active API keys.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where(ApiKeyConstant::IS_ACTIVE, true)
            ->where(function ($q) {
                $q->whereNull(ApiKeyConstant::EXPIRES_AT)
                    ->orWhere(ApiKeyConstant::EXPIRES_AT, '>', now());
            });
    }
}
