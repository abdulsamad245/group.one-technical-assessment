<?php

namespace App\Models;

use App\Constants\UserConstant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 *
 * Represents a user in the system with role-based access control.
 * Users are scoped to brands (multi-tenancy).
 *
 * @property string $id
 * @property string $brand_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role (user, admin, super_admin)
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Brand $brand
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuid;
    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = UserConstant::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        UserConstant::BRAND_ID,
        UserConstant::NAME,
        UserConstant::EMAIL,
        UserConstant::PASSWORD,
        UserConstant::ROLE,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        UserConstant::PASSWORD,
        UserConstant::REMEMBER_TOKEN,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            UserConstant::EMAIL_VERIFIED_AT => 'datetime',
            UserConstant::PASSWORD => 'hashed',
        ];
    }

    /**
     * Get the brand that owns the user.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->{UserConstant::ROLE} === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->{UserConstant::ROLE}, [UserConstant::ROLE_ADMIN, UserConstant::ROLE_SUPER_ADMIN]);
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->{UserConstant::ROLE} === UserConstant::ROLE_SUPER_ADMIN;
    }

    /**
     * Scope a query to only include users of a specific role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where(UserConstant::ROLE, $role);
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn(UserConstant::ROLE, [UserConstant::ROLE_ADMIN, UserConstant::ROLE_SUPER_ADMIN]);
    }
}