<?php

namespace App\Exceptions;

use Exception;

/**
 * Base exception for all API domain exceptions.
 *
 * This exception class supports translation keys and parameters
 * for internationalized error messages.
 */
abstract class ApiException extends Exception
{
    /**
     * Create a new API exception instance.
     *
     * @param  string  $messageKey  Translation key for the error message
     * @param  int  $status  HTTP status code
     * @param  string  $errorCode  Error code for API clients
     * @param  array<string, mixed>  $params  Translation parameters
     */
    public function __construct(
        protected string $messageKey,
        protected int $status,
        protected string $errorCode,
        protected array $params = []
    ) {
        parent::__construct(__($messageKey, $params));
    }

    /**
     * Get the HTTP status code.
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Get the error code for API clients.
     */
    public function errorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get the translation key.
     */
    public function messageKey(): string
    {
        return $this->messageKey;
    }

    /**
     * Get the translation parameters.
     *
     * @return array<string, mixed>
     */
    public function params(): array
    {
        return $this->params;
    }
}
