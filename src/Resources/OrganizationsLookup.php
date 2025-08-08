<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class OrganizationsLookup
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string|int, mixed>
     */
    public function byVanityName(string $vanityName): array
    {
        return $this->client->get('organizations?q=vanityName&vanityName=' . rawurlencode($vanityName), [], false);
    }

    /**
     * @return array<string|int, mixed>
     */
    public function byEmailDomain(string $emailDomain): array
    {
        return $this->client->get('organizations?q=emailDomain&emailDomain=' . rawurlencode($emailDomain), [], false);
    }

    /**
     * @return array<string|int, mixed>
     */
    public function projection(int $organizationId, string $projection): array
    {
        return $this->client->get('organizations/' . $organizationId . '?projection=' . rawurlencode($projection), [], false);
    }

    public static function idToUrn(int $id): string
    {
        return 'urn:li:organization:' . $id;
    }

    public static function urnToId(string $urn): ?int
    {
        if (!str_starts_with($urn, 'urn:li:organization:')) {
            return null;
        }
        $parts = explode(':', $urn);
        return isset($parts[3]) ? (int) $parts[3] : null;
    }
}
