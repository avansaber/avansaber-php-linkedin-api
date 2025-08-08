<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\OrganizationsFollowerStats;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrganizationsFollowerStatsTest extends TestCase
{
    public function test_lifetime_and_timebound(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $stats = new OrganizationsFollowerStats($client);

        $lifetime = $stats->lifetime('urn:li:organization:123');
        $this->assertArrayHasKey('elements', $lifetime);

        $tb = $stats->timeBound('urn:li:organization:123', 'DAY', 1000, 2000);
        $this->assertArrayHasKey('elements', $tb);
    }
}
