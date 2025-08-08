<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\Organizations;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrganizationsTest extends TestCase
{
    public function test_get_organization_v2(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 123,
            'localizedName' => 'TestOrg',
            'vanityName' => 'testorg',
            'primaryOrganizationType' => 'NONE',
        ])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $orgs = new Organizations($client);

        $org = $orgs->get(123);
        $this->assertSame(123, $org->id);
        $this->assertSame('TestOrg', $org->localizedName);

        // get by URN
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 123,
            'localizedName' => 'TestOrg',
            'vanityName' => 'testorg',
            'primaryOrganizationType' => 'NONE',
        ])));
        $this->assertSame(123, $orgs->getByUrn('urn:li:organization:123')->id);
    }
}
