<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseKeyInvalidException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_key_invalid',
            404,
            'LICENSE_KEY_INVALID'
        );
    }
}
