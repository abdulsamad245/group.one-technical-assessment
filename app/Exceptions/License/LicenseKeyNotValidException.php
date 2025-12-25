<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseKeyNotValidException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_key_not_valid',
            403,
            'LICENSE_KEY_NOT_VALID'
        );
    }
}
