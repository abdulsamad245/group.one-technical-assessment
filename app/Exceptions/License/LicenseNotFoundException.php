<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_not_found',
            404,
            'LICENSE_NOT_FOUND'
        );
    }
}
