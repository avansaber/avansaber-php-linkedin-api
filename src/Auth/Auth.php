<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

final class Auth
{
    /**
     * Build the LinkedIn Authorization URL (Authorization Code Flow). PKCE optional.
     *
     * @param array<int, string> $scopes
     */
    public function getAuthUrl(string $clientId, string $redirectUri, array $scopes, ?Pkce $pkce, string $state): string
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => implode(' ', $scopes),
            'state' => $state,
        ];
        if ($pkce) {
            $params['code_challenge'] = $pkce->getCodeChallenge();
            $params['code_challenge_method'] = 'S256';
        }
        return 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Exchange authorization code for access token. If using PKCE, include code_verifier.
     * Returns parameters for the HTTP call to be performed by the host app.
     *
     * @return array<string, mixed>
     */
    public function getAccessToken(string $clientId, string $clientSecret, string $code, string $redirectUri, ?Pkce $pkce): array
    {
        return [
            'endpoint' => 'https://www.linkedin.com/oauth/v2/accessToken',
            'method' => 'POST',
            'params' => array_filter([
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code_verifier' => $pkce?->getCodeVerifier(),
            ], static fn($v) => $v !== null),
        ];
    }
}

final class Pkce
{
    private string $codeVerifier;
    private string $codeChallenge;

    public function __construct(string $codeVerifier, string $codeChallenge)
    {
        $this->codeVerifier = $codeVerifier;
        $this->codeChallenge = $codeChallenge;
    }

    public function getCodeVerifier(): string
    {
        return $this->codeVerifier;
    }

    public function getCodeChallenge(): string
    {
        return $this->codeChallenge;
    }
}
