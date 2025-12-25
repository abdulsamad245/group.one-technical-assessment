<?php

namespace App\Exceptions\ApiKey;

use App\Exceptions\ApiException;

class ApiKeyNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            'messages.api_key_not_found',
            404,
            'API_KEY_NOT_FOUND'
        );
    }
}
