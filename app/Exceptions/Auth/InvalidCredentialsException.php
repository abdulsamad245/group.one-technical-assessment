<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ApiException;

class InvalidCredentialsException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.invalid_credentials',
            401,
            'INVALID_CREDENTIALS'
        );
    }
}
