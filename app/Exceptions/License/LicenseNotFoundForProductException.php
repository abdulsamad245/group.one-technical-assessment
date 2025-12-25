<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseNotFoundForProductException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_not_found_for_product',
            404,
            'LICENSE_NOT_FOUND_FOR_PRODUCT'
        );
    }
}
