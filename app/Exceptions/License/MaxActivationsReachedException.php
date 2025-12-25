<?php

namespace App\Exceptions\License;

use App\Exceptions\ApiException;

class MaxActivationsReachedException extends ApiException
{
    public function __construct(string $instanceType, int $max)
    {
        parent::__construct(
            'messages.max_activations_reached',
            409,
            'MAX_ACTIVATIONS_REACHED',
            [
                'instance_type' => $instanceType,
                'max' => $max,
            ]
        );
    }
}
