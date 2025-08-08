<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class OrganizationsFollowerStats
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function lifetime(string $organizationUrn): array
    {
        return $this->client->get('organizationalEntityFollowerStatistics?q=organizationalEntity&organizationalEntity=' . rawurlencode($organizationUrn), [], false);
    }

    /**
     * @return array<string|int, mixed>
     */
    public function timeBound(string $organizationUrn, string $granularity, int $startMillis, int $endMillis): array
    {
        $query = 'organizationalEntityFollowerStatistics?q=organizationalEntity'
            . '&organizationalEntity=' . rawurlencode($organizationUrn)
            . '&timeIntervals.timeGranularityType=' . rawurlencode($granularity)
            . '&timeIntervals.timeRange.start=' . $startMillis
            . '&timeIntervals.timeRange.end=' . $endMillis;
        return $this->client->get($query, [], false);
    }
}
