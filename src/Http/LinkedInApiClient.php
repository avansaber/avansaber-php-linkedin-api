<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Http;

use Avansaber\LinkedInApi\Exceptions\ApiException;
use Avansaber\LinkedInApi\Exceptions\AuthenticationException;
use Avansaber\LinkedInApi\Exceptions\NotFoundException;
use Avansaber\LinkedInApi\Exceptions\PermissionException;
use Avansaber\LinkedInApi\Exceptions\RateLimitException;
use Avansaber\LinkedInApi\Exceptions\ServerException;
use Avansaber\LinkedInApi\Exceptions\ValidationException;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

final class LinkedInApiClient
{
    private HttpClientInterface $httpClient;
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;
    private ClientConfig $config;
    private string $accessToken;
    private ?LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ClientConfig $config,
        string $accessToken
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->config = $config;
        $this->accessToken = $accessToken;
        $this->logger = $config->getLogger();
    }

    public function get(string $path, array $headers = [], bool $useRestBase = true): array
    {
        $response = $this->request('GET', $path, null, $headers, $useRestBase);
        return $this->decodeJson($response);
    }

    public function post(string $path, array $body = null, array $headers = [], bool $useRestBase = true): array
    {
        $payload = $body === null ? null : json_encode($body, JSON_UNESCAPED_SLASHES);
        $response = $this->request('POST', $path, $payload, $headers, $useRestBase);
        return $this->decodeJson($response);
    }

    public function request(string $method, string $path, ?string $body, array $headers, bool $useRestBase): ResponseInterface
    {
        $base = $useRestBase ? $this->config->getRestBaseUri() : $this->config->getV2BaseUri();
        $uri = rtrim($base, '/') . '/' . ltrim($path, '/');

        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Authorization', 'Bearer ' . $this->accessToken)
            ->withHeader('Accept', 'application/json')
            ->withHeader('LinkedIn-Version', $this->config->getLinkedInVersion())
            ->withHeader('X-Restli-Protocol-Version', '2.0.0')
            ->withHeader('User-Agent', 'avansaber-php-linkedin-api/1.x');

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, (string) $value);
        }

        if ($body !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream($body));
        }

        if ($this->logger) {
            $this->logger->debug('LinkedIn request', [
                'method' => $method,
                'uri' => (string) $uri,
                'headers' => $this->redactSensitiveHeaders($request->getHeaders()),
                'body' => $body,
            ]);
        }

        $response = $this->httpClient->sendRequest($request);

        $this->throwIfError($response);

        return $response;
    }

    private function throwIfError(ResponseInterface $response): void
    {
        $status = $response->getStatusCode();
        if ($status < 400) {
            return;
        }

        $correlationId = $response->getHeaderLine('X-LI-UUID') ?: null;
        $payload = $this->safeDecode((string) $response->getBody());
        $message = $payload['message'] ?? 'LinkedIn API error';
        $serviceErrorCode = isset($payload['serviceErrorCode']) ? (int) $payload['serviceErrorCode'] : null;

        if ($status === 401) {
            throw new AuthenticationException($message, 401, $serviceErrorCode, $correlationId);
        }
        if ($status === 403) {
            throw new PermissionException($message, 403, $serviceErrorCode, $correlationId);
        }
        if ($status === 404) {
            throw new NotFoundException($message, 404, $serviceErrorCode, $correlationId);
        }
        if ($status === 400) {
            throw new ValidationException($message, 400, $serviceErrorCode, $correlationId);
        }
        if ($status === 429) {
            $retryAfterHeader = $response->getHeaderLine('Retry-After');
            $retryAfter = is_numeric($retryAfterHeader) ? (int) $retryAfterHeader : null;
            throw new RateLimitException($message, 429, $retryAfter, $serviceErrorCode, $correlationId);
        }
        if ($status >= 500) {
            throw new ServerException($message, $status, $serviceErrorCode, $correlationId);
        }

        throw new ApiException($message, $status, $serviceErrorCode, $correlationId);
    }

    private function decodeJson(ResponseInterface $response): array
    {
        $decoded = $this->safeDecode((string) $response->getBody());
        return is_array($decoded) ? $decoded : [];
    }

    private function safeDecode(string $json): array
    {
        if ($json === '') {
            return [];
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Redact sensitive headers like Authorization.
     *
     * @param array<string, array<int, string>> $headers
     * @return array<string, array<int, string>>
     */
    private function redactSensitiveHeaders(array $headers): array
    {
        if (isset($headers['Authorization'])) {
            $headers['Authorization'] = ['REDACTED'];
        }
        return $headers;
    }
}
