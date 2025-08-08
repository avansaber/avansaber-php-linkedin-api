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
     * Fetch the current user's profile. Attempt REST base first, then v2 fallback.
     *
     * @return array<string, mixed>
     */
    public function get(): array
    {
        try {
            return $this->client->get('me', [], true);
        } catch (ApiException $e) {
            return $this->client->get('me', [], false);
        }
    }
}
