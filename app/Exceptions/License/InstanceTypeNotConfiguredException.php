<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class InstanceTypeNotConfiguredException extends ApiException
{
    public function __construct(string $instanceType)
    {
        parent::__construct(
            'messages.instance_type_not_configured',
            400,
            'INSTANCE_TYPE_NOT_CONFIGURED',
            ['instance_type' => $instanceType]
        );
    }
}
