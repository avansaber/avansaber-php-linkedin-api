<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\AdAccounts;
use Avansaber\LinkedInApi\Resources\AdCampaignGroups;
use Avansaber\LinkedInApi\Resources\AdCampaigns;
use Avansaber\LinkedInApi\Data\Requests\CampaignCreateRequest;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AdResourcesTest extends TestCase
{
    public function test_ad_accounts_and_campaigns(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => '123'])));
        $fake->queueResponse(new Response(201, ['Content-Type' => 'application/json'], json_encode(['id' => '456'])));
        $fake->queueResponse(new Response(201, ['Content-Type' => 'application/json'], json_encode(['id' => '456'])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');

        $acc = new AdAccounts($client);
        $this->assertArrayHasKey('elements', $acc->findByAuthenticatedUser());

        $groups = new AdCampaignGroups($client);
        $this->assertArrayHasKey('elements', $groups->search('urn:li:sponsoredAccount:1'));

        $campaigns = new AdCampaigns($client);
        $this->assertSame('123', $campaigns->get('123')['id']);
        $this->assertSame('456', $campaigns->create('urn:li:sponsoredAccount:1', ['name' => 'New'])['id']);
        $dto = new CampaignCreateRequest('urn:li:sponsoredAccount:1', 'New');
        $this->assertSame('456', $campaigns->createWithDto($dto)['id']);
    }
}
