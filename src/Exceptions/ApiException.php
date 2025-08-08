<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Exceptions;

use RuntimeException;

class ApiException extends RuntimeException
{
    private int $statusCode;
    private ?int $serviceErrorCode;
    private ?string $correlationId;

    public function __construct(string $message, int $statusCode = 0, ?int $serviceErrorCode = null, ?string $correlationId = null)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->serviceErrorCode = $serviceErrorCode;
        $this->correlationId = $correlationId;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getServiceErrorCode(): ?int
    {
        return $this->serviceErrorCode;
    }

    public function getCorrelationId(): ?string
    {
        return $this->correlationId;
    }
}
