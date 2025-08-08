<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class OAuthClient
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory
    ) {
    }

    /**
     * Exchange authorization code for access token. Supports PKCE via optional code_verifier.
     *
     * @return array<string, mixed>
     */
    public function exchangeAuthorizationCode(
        string $clientId,
        string $clientSecret,
        string $code,
        string $redirectUri,
        ?string $codeVerifier = null
    ): array {
        $endpoint = 'https://www.linkedin.com/oauth/v2/accessToken';
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];
        if ($codeVerifier !== null) {
            $params['code_verifier'] = $codeVerifier;
        }

        $request = $this->requestFactory->createRequest('POST', $endpoint)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream(http_build_query($params, '', '&', PHP_QUERY_RFC3986)));

        $response = $this->httpClient->sendRequest($request);
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Attempt refresh token exchange (if your LinkedIn app issues refresh tokens).
     *
     * @return array<string, mixed>
     */
    public function refresh(string $clientId, string $clientSecret, string $refreshToken): array
    {
        $endpoint = 'https://www.linkedin.com/oauth/v2/accessToken';
        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];

        $request = $this->requestFactory->createRequest('POST', $endpoint)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Accept', 'application/json')
            ->withBody($this->streamFactory->createStream(http_build_query($params, '', '&', PHP_QUERY_RFC3986)));

        $response = $this->httpClient->sendRequest($request);
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        return is_array($data) ? $data : [];
    }
}
