<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Exceptions\ApiException;

final class Me
{
    private LinkedInApiClient $client;

    public function __construct(LinkedInApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Fetch the current user's profile using modern OpenID Connect userinfo endpoint.
     * Falls back to legacy endpoints if needed.
     *
     * @return array<string, mixed>
     */
    public function get(): array
    {
        // Try modern OpenID Connect userinfo endpoint first
        try {
            return $this->getUserInfo();
        } catch (ApiException $e) {
            // Fallback to legacy REST endpoint
            try {
                return $this->client->get('me', [], true);
            } catch (ApiException $e2) {
                // Final fallback to v2 endpoint
                return $this->client->get('me', [], false);
            }
        }
    }

    /**
     * Get user info using OpenID Connect userinfo endpoint (recommended).
     * 
     * This endpoint works with modern scopes: openid, profile, email
     * 
     * @return array<string, mixed>
     */
    public function getUserInfo(): array
    {
        return $this->client->get('userinfo', [], false); // v2 endpoint
    }

    /**
     * Get user profile using legacy v2/people endpoint.
     * 
     * @deprecated Use getUserInfo() with modern scopes instead
     * @return array<string, mixed>
     */
    public function getLegacyProfile(): array
    {
        return $this->client->get('people/~', [], false);
    }

    /**
     * Get user profile with specific projection (legacy v2 endpoint).
     * 
     * @deprecated Use getUserInfo() with modern scopes instead
     * @param string $projection LinkedIn API projection string
     * @return array<string, mixed>
     */
    public function getWithProjection(string $projection): array
    {
        return $this->client->get('people/~', ['projection' => $projection], false);
    }
}
