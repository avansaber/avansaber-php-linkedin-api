<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class AdAccounts
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function findByAuthenticatedUser(): array
    {
        return $this->client->get('adAccountUsers?q=authenticatedUser', [], false);
    }
}
