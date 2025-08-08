<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Http;

use Psr\Log\LoggerInterface;

final class ClientConfig
{
    public const DEFAULT_REST_BASE_URI = 'https://api.linkedin.com/rest/';
    public const DEFAULT_V2_BASE_URI = 'https://api.linkedin.com/v2/';

    private string $restBaseUri;
    private string $v2BaseUri;
    private string $linkedInVersion;
    private int $timeoutSeconds;
    private ?LoggerInterface $logger;

    public function __construct(
        string $linkedInVersion = '202401',
        string $restBaseUri = self::DEFAULT_REST_BASE_URI,
        string $v2BaseUri = self::DEFAULT_V2_BASE_URI,
        int $timeoutSeconds = 30,
        ?LoggerInterface $logger = null
    ) {
        $this->linkedInVersion = $linkedInVersion;
        $this->restBaseUri = rtrim($restBaseUri, '/') . '/';
        $this->v2BaseUri = rtrim($v2BaseUri, '/') . '/';
        $this->timeoutSeconds = $timeoutSeconds;
        $this->logger = $logger;
    }

    public function getRestBaseUri(): string
    {
        return $this->restBaseUri;
    }

    public function getV2BaseUri(): string
    {
        return $this->v2BaseUri;
    }

    public function getLinkedInVersion(): string
    {
        return $this->linkedInVersion;
    }

    public function getTimeoutSeconds(): int
    {
        return $this->timeoutSeconds;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
