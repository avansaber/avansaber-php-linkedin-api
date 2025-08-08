<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Fixtures;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeHttpClient implements ClientInterface
{
    /** @var array<int, ResponseInterface> */
    private array $responses = [];

    private ?RequestInterface $lastRequest = null;

    public function queueResponse(ResponseInterface $response): void
    {
        $this->responses[] = $response;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;
        if (count($this->responses) === 0) {
            throw new \RuntimeException('No queued response');
        }
        return array_shift($this->responses);
    }

    public function getLastRequest(): ?RequestInterface
    {
        return $this->lastRequest;
    }
}
