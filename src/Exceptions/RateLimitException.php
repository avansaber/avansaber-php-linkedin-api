<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Exceptions;

class RateLimitException extends ApiException
{
    private ?int $retryAfterSeconds;

    public function __construct(string $message, int $statusCode = 429, ?int $retryAfterSeconds = null, ?int $serviceErrorCode = null, ?string $correlationId = null)
    {
        parent::__construct($message, $statusCode, $serviceErrorCode, $correlationId);
        $this->retryAfterSeconds = $retryAfterSeconds;
    }

    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfterSeconds;
    }
}
