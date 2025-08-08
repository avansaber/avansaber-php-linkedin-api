<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Data\Requests\CampaignCreateRequest;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class AdCampaigns
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function get(string $campaignId): array
    {
        return $this->client->get('adCampaigns/' . rawurlencode($campaignId), [], false);
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string|int, mixed>
     */
    public function create(string $accountUrn, array $data): array
    {
        $payload = $data + ['account' => $accountUrn];
        return $this->client->post('adCampaigns', $payload, [], false);
    }

    /**
     * @return array<string|int, mixed>
     */
    public function createWithDto(CampaignCreateRequest $request): array
    {
        return $this->client->post('adCampaigns', $request->toRequestBody(), [], false);
    }
}
