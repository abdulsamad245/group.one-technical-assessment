<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class ActivationNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.activation_not_found',
            404,
            'ACTIVATION_NOT_FOUND'
        );
    }
}
