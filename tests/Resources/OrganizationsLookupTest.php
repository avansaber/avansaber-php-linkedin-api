<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\OrganizationsLookup;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrganizationsLookupTest extends TestCase
{
    public function test_id_urn_helpers_and_projection(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 123,
            'vanityName' => 'demo'
        ])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $lookup = new OrganizationsLookup($client);

        $urn = OrganizationsLookup::idToUrn(123);
        $this->assertSame('urn:li:organization:123', $urn);
        $this->assertSame(123, OrganizationsLookup::urnToId($urn));

        $data = $lookup->projection(123, '(vanityName,id)');
        $this->assertSame(123, $data['id']);
        $this->assertSame('demo', $data['vanityName']);
    }
}
