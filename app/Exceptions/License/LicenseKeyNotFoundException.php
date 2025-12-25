<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseKeyNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_key_not_found',
            404,
            'LICENSE_KEY_NOT_FOUND'
        );
    }
}
