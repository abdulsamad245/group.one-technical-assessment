<?php

namespace App\Exceptions\Brand;

use App\Exceptions\ApiException;

class BrandNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.brand_not_found',
            404,
            'BRAND_NOT_FOUND'
        );
    }
}
