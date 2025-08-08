<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class AdCampaignGroups
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function search(string $accountUrn): array
    {
        return $this->client->get('adCampaignGroups?q=search&search.account.values=List(' . rawurlencode($accountUrn) . ')', [], false);
    }
}
