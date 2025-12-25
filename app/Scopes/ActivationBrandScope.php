<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Global scope to filter activations by authenticated brand.
 *
 * This scope filters through the license relationship to get brand_id.
 * The brand is determined from API key authentication or user authentication.
 */
class ActivationBrandScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $brand = app()->bound('current_brand') ? app('current_brand') : null;

        if ($brand) {
            $builder->whereHas('license', function ($query) use ($brand) {
                $query->where('brand_id', $brand->id);
            });

            return;
        }

        $user = auth()->user();

        if (! $user) {
            return;
        }

        if ($user->isSuperAdmin()) {
            return;
        }

        $builder->whereHas('license', function ($query) use ($user) {
            $query->where('brand_id', $user->brand_id);
        });
    }
}
