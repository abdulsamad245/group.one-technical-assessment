<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class LicenseCannotActivateException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.license_cannot_activate',
            403,
            'LICENSE_CANNOT_ACTIVATE'
        );
    }
}
