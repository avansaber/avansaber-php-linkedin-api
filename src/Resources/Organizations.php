<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Data\Organization;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class Organizations
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    public function get(int $organizationId): Organization
    {
        $data = $this->client->get('organizations/' . $organizationId, [], false);
        return Organization::fromArray($data);
    }

    public function getByUrn(string $organizationUrn): Organization
    {
        $id = OrganizationsLookup::urnToId($organizationUrn);
        if ($id === null) {
            throw new \InvalidArgumentException('Invalid organization URN: ' . $organizationUrn);
        }
        return $this->get($id);
    }
}
